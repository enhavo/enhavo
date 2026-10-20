import {AbstractTab} from "./AbstractTab";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {Translator} from "@enhavo/framework/translation/Translator";
import {ResourceInputManager} from "@enhavo/app/manager/ResourceInputManager";
import {Event} from "@enhavo/app/frame/FrameEventDispatcher";
import {TabInterface} from "@enhavo/app/tab/TabInterface";

export class RevisionTab extends AbstractTab
{
    public revisions: Revision[];
    public token: string;
    public restore: boolean = true;
    public diff: boolean = false;
    public compare: string = 'previous';
    public diffTexts: {[key: string]: string} = {};

    public openId: number = null;
    public loadingId: number = null;
    public errorId: number = null;
    public changes: {[revisionId: number]: Change[]} = {};
    public first: {[revisionId: number]: boolean} = {};
    public dates: {[revisionId: number]: {older: string, newer: string}} = {};

    public confirmMessage: string;
    public confirmLabelOk: string;
    public confirmLabelCancel: string;
    public userLabel: string;

    constructor(
        private readonly uiManager: UiManager,
        private readonly flashMessenger: FlashMessenger,
        private readonly frameManager: FrameManager,
        private readonly translator: Translator,
        private readonly resourceInputManager: ResourceInputManager,
    ) {
        super();
    }

    async activateRevision(revision: Revision)
    {
        this.uiManager.confirm({
            message: this.confirmMessage,
            denyLabel: this.confirmLabelCancel,
            acceptLabel: this.confirmLabelOk,
        }).then((accept: boolean) => {
            if (accept) {
                this.sendActivate(revision);
            }
        });
    }

    private async sendActivate(revision: Revision)
    {
        this.uiManager.loading(true);

        let response = await fetch(revision.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                token: this.token
            }),
        });

        if (response.ok) {
            this.frameManager.dispatch(new Event('input_changed'));
            this.flashMessenger.success(this.translator.trans('enhavo_app.revision.message.restored', {}, 'javascript'));
            await this.resourceInputManager.load(this.resourceInputManager.url);
            (await this.frameManager.getFrame()).loaded = false;
            this.uiManager.loading(false);
            window.location.reload();
        } else {
            this.uiManager.loading(false);
            this.flashMessenger.error(this.translator.trans('enhavo_app.error', {}, 'javascript'));
        }
    }

    async toggle(revision: Revision): Promise<void>
    {
        if (!this.diff) {
            return;
        }

        if (this.openId === revision.id) {
            this.openId = null;
            return;
        }

        this.openId = revision.id;
        if (this.changes[revision.id]) {
            return;
        }

        this.loadingId = revision.id;
        this.errorId = null;
        try {
            const response = await fetch(revision.diffUrl, {headers: {'Accept': 'application/json'}});
            if (!response.ok) {
                throw new Error('Status ' + response.status);
            }
            const data = await response.json();
            this.first[revision.id] = data.first;
            this.dates[revision.id] = {older: data.olderDate, newer: data.newerDate};
            this.changes[revision.id] = data.changes;
        } catch (error) {
            console.error(error);
            this.errorId = revision.id;
        } finally {
            this.loadingId = null;
        }
    }

    /**
     * Groups consecutive changes by their section (resource fields or a block).
     */
    getSections(revisionId: number): ChangeGroup[]
    {
        const groups: ChangeGroup[] = [];
        for (const change of this.changes[revisionId] ?? []) {
            // fields of the resource itself come first and get no heading
            const label = change.section ? change.section.label : '';
            const last = groups[groups.length - 1];
            if (last && last.label === label) {
                last.changes.push(change);
            } else {
                groups.push({label: label, status: change.section?.status ?? 'changed', changes: [change]});
            }
        }
        return groups;
    }

    /**
     * Column title with the date of the compared state, e.g. "Before (14.10.2026 10:32:30)".
     */
    getColumnLabel(revisionId: number, column: 'older'|'newer'): string
    {
        const date = this.dates[revisionId]?.[column];

        return date ? `${this.diffTexts[column]} (${this.formatDate(date)})` : this.diffTexts[column];
    }

    formatDate(value: string): string
    {
        if (!value) {
            return '-';
        }
        const date = new Date(value);
        const pad = (number: number) => String(number).padStart(2, '0');

        return `${pad(date.getDate())}.${pad(date.getMonth() + 1)}.${date.getFullYear()} ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
    }

    /**
     * Word based diff, so the changed words of a long text can be highlighted.
     */
    diffWords(oldValue: string, newValue: string): {old: DiffPart[], new: DiffPart[]}
    {
        const oldWords = (oldValue ?? '').split(/(\s+)/).filter(word => word !== '');
        const newWords = (newValue ?? '').split(/(\s+)/).filter(word => word !== '');

        // avoid a huge table for very long texts, show them as replaced instead
        if (oldWords.length * newWords.length > 250000) {
            return {
                old: oldValue ? [{text: oldValue, changed: true}] : [],
                new: newValue ? [{text: newValue, changed: true}] : [],
            };
        }

        const lcs: number[][] = Array.from({length: oldWords.length + 1}, () => new Array(newWords.length + 1).fill(0));
        for (let i = oldWords.length - 1; i >= 0; i--) {
            for (let j = newWords.length - 1; j >= 0; j--) {
                lcs[i][j] = oldWords[i] === newWords[j] ? lcs[i + 1][j + 1] + 1 : Math.max(lcs[i + 1][j], lcs[i][j + 1]);
            }
        }

        const oldParts: DiffPart[] = [];
        const newParts: DiffPart[] = [];
        const push = (parts: DiffPart[], text: string, changed: boolean) => {
            const last = parts[parts.length - 1];
            if (last && last.changed === changed) {
                last.text += text;
            } else {
                parts.push({text: text, changed: changed});
            }
        };

        let i = 0;
        let j = 0;
        while (i < oldWords.length || j < newWords.length) {
            if (i < oldWords.length && j < newWords.length && oldWords[i] === newWords[j]) {
                push(oldParts, oldWords[i], false);
                push(newParts, newWords[j], false);
                i++;
                j++;
            } else if (j < newWords.length && (i >= oldWords.length || lcs[i][j + 1] >= lcs[i + 1][j])) {
                push(newParts, newWords[j], true);
                j++;
            } else {
                push(oldParts, oldWords[i], true);
                i++;
            }
        }

        return {old: oldParts, new: newParts};
    }

    morph(source: TabInterface): void
    {
        super.morph(source);
        this.revisions = (source as RevisionTab).revisions;
        this.restore = (source as RevisionTab).restore;
        this.diff = (source as RevisionTab).diff;
        this.compare = (source as RevisionTab).compare;
        this.diffTexts = (source as RevisionTab).diffTexts;
        // comparisons with the previous revision stay valid, comparisons with the current state are outdated after a save
        if (this.compare === 'current') {
            this.changes = {};
            this.first = {};
            this.dates = {};
        }
        this.openId = null;
        this.errorId = null;
    }
}

export class Revision
{
    id: number;
    date: string;
    url: string|null;
    diffUrl: string|null;
    user: object;
}

export class Change
{
    section: {label: string, status: string}|null;
    field: string|null;
    old: string|null;
    new: string|null;
}

export class ChangeGroup
{
    label: string;
    status: string;
    changes: Change[];
}

export class DiffPart
{
    text: string;
    changed: boolean;
}
