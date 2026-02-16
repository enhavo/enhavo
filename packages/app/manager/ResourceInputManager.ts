import {ActionManager} from "@enhavo/app/action/ActionManager";
import {ActionInterface} from "@enhavo/app/action/ActionInterface";
import {RouteContainer} from "@enhavo/app/routing/RouteContainer";
import {TabManager} from "@enhavo/app/tab/TabManager";
import {TabInterface} from "@enhavo/app/tab/TabInterface";
import {Form} from "@enhavo/vue-form/model/Form";
import {FormFactory} from "@enhavo/vue-form/form/FormFactory";
import {FormUtil} from "@enhavo/vue-form/form/FormUtil";
import {FormVisitorInterface} from "@enhavo/vue-form/form/FormVisitor";
import {Theme} from "@enhavo/vue-form/form/Theme";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {VueRouterFactory} from "@enhavo/app/vue/VueRouterFactory";
import {Event} from "@enhavo/app/frame/FrameEventDispatcher";
import {ClientInterface, Transport} from "@enhavo/app/client/ClientInterface";
import {Translator} from "@enhavo/app/translation/Translator";
import {Metadata} from "@enhavo/app/model/Metadata";

export class ResourceInputManager
{
    public url: string;
    public actions: ActionInterface[];
    public actionsSecondary: ActionInterface[];
    public tabs: TabInterface[] = null;
    public routes: RouteContainer;
    public form: Form;
    public resource: object;
    public metadata: Metadata;

    private visitors: FormVisitorInterface[] = [];
    private loadedPromiseResolveCalls: Array<() => void> = [];
    private loaded: boolean = false;
    private saveQueue: Promise<Transport>[] = [];

    constructor(
        private actionManager: ActionManager,
        private tabManager: TabManager,
        private formFactory: FormFactory,
        private frameManager: FrameManager,
        private vueRouterFactory: VueRouterFactory,
        private client: ClientInterface,
        private translator: Translator,
    ) {
    }

    async load(url: string)
    {
        const transport = await this.client.fetch(url);

        if (!transport.ok || !transport.response.ok) {
            this.frameManager.loaded();
            await this.client
                .handleError(transport, {
                    terminate: true,
                    confirm: true,
                });
            return;
        }

        const data = await transport.response.json();

        this.url = data.url;
        this.resource = data.resource;

        this.routes = new RouteContainer(data.routes);
        this.form = this.formFactory.create(data.form, this.visitors);
        this.tabs = this.tabManager.createTabs(data.tabs);
        this.metadata = data.metadata;

        this.actions = this.actionManager.createActions(data.actions);
        this.actionsSecondary = this.actionManager.createActions(data.actionsSecondary);

        this.initTab(window.location.href);
        this.updateTabs();

        this.frameManager.loaded();

        if (this.resource === null) {
            this.frameManager.setLabel(this.translator.trans('enhavo_app.create.label.title', {label: this.metadata.label}, 'javascript'));
        } else {
            this.frameManager.setLabel(this.translator.trans('enhavo_app.update.label.title', {label: this.metadata.label}, 'javascript'));
        }

        this.loaded = true
        for (let promise of this.loadedPromiseResolveCalls) {
            promise();
        }
    }

    onLoaded(): Promise<void>
    {
        return new Promise(resolve => {
            if (this.loaded) {
                resolve();
            } else {
                this.loadedPromiseResolveCalls.push(resolve);
            }
        })
    }

    /**
     * @param url Use null to use the manager url. If save request is in queue, then the url will be resolved on its turn.
     * @param morph
     */
    async save(url: string = null, morph: boolean  = false): Promise<Transport>
    {
        // to synchronize parallel saves, we execute them one by one, using a promise queue

        let localQueue = [];
        for (let promise of this.saveQueue) {
            localQueue.push(promise);
        }

        let promise: Promise<Transport> = new Promise((resolve, reject) => {
            Promise.allSettled(localQueue).then(async () => {
                let onUrl = url == null ? this.url : url;
                let transport = null;
                try {
                    transport = await this.doSave(onUrl, morph);
                } catch (err) {
                    this.saveQueue.splice(this.saveQueue.indexOf(promise), 1);
                    reject(err);
                    return;
                }
                this.saveQueue.splice(this.saveQueue.indexOf(promise), 1);
                resolve(transport);
            })
        });

        this.saveQueue.push(promise)
        return promise;
    }

    private async doSave(url: string, morph: boolean  = false): Promise<Transport>
    {
        const transport = await this.sendForm(url);

        if (!transport.ok || !transport.response.ok && transport.response.status !== 400) {
            return transport;
        }

        await this.handleResponse(transport.response, morph);

        return transport;
    }

    public async handleResponse(response: Response, morph: boolean  = false): Promise<void>
    {
        const data = await response.json();

        this.url = data.url;
        this.resource = data.resource;
        this.routes = new RouteContainer(data.routes);

        if (morph) {
            this.form.morphStart();
            let newForm = this.formFactory.create(data.form, this.visitors);
            this.form.morphMerge(newForm);
            this.form.morphFinish();

            this.actionManager.morphActions(this.actions, this.actionManager.createActions(data.actions));
            this.actionManager.morphActions(this.actionsSecondary, this.actionManager.createActions(data.actionsSecondary));

            this.tabManager.morphTabs(this.tabs, this.tabManager.createTabs(data.tabs));
            this.updateTabs();
        } else {
            this.form.destroy();
            this.form = this.formFactory.create(data.form, this.visitors);

            this.actions = this.actionManager.createActions(data.actions);
            this.actionsSecondary = this.actionManager.createActions(data.actionsSecondary);

            // ToDo: Overwrite Tabs, but keep active state
            // this.tabs = this.tabManager.createTabs(data.tabs);
        }

        if (data.redirect) {
            await this.redirect(data.redirect);
        }
    }

    public async redirect(url: string)
    {
        this.url = url;
        const activeTab = this.getActiveTab()?.key;
        const targetUrl = new URL(url, window.location.origin);

        if (activeTab) {
            targetUrl.searchParams.set('tab', activeTab);
        }

        await this.vueRouterFactory.getRouter().push({
            path: targetUrl.pathname,
            query: Object.fromEntries(targetUrl.searchParams.entries()),
        });

        const frame = await this.frameManager.getFrame();
        frame.url = targetUrl.toString();
        this.frameManager.save();
    }

    async sendForm(url: string, signal: AbortSignal = null): Promise<Transport>
    {
        const formData = FormUtil.serializeForm(this.form);
        const transport = await this.client.fetch(url, {
            method: 'POST',
            body: formData,
            signal: signal
        });

        return transport;
    }

    public async selectTab(tabKey: string)
    {
        for (let loopTab of this.tabs) {
            if (loopTab.key === tabKey && loopTab.active) {
                return;
            }
        }

        if (this.getQueryTabValue(window.location.href) != tabKey) {
            await this.vueRouterFactory.getRouter().push({ query: { tab: tabKey } })
            let frame = await this.frameManager.getFrame();
            if (frame) {
                frame.url = window.location.href;
            }
            this.frameManager.save();
        }

        for (let loopTab of this.tabs) {
            loopTab.active = loopTab.key === tabKey;
        }
    }

    private getActiveTab(): TabInterface
    {
        for (let tab of this.tabs) {
            if (tab.active) {
                return tab;
            }
        }

        return null;
    }

    private getQueryTabValue(url: string): string
    {
        const uri = new URL(url, window.location.href);
        return uri.searchParams.get('tab');
    }

    public initTab(url: string)
    {
        if (this.tabs === null) {
            return;
        }

        const tabKey = this.getQueryTabValue(url);
        if (tabKey) {
            this.selectTab(tabKey);
        } else {
            this.selectFirstTab();
        }
    }

    private updateTabs()
    {
        for (let tab of this.tabs) {
            tab.update({
                form: this.form,
            });
        }
    }

    private selectFirstTab()
    {
        for (let tab of this.tabs) {
            tab.active = false;
        }

        if (this.tabs.length > 0) {
            this.tabs[0].active = true;
        }
    }

    public addVisitor(visitor: FormVisitorInterface)
    {
        this.visitors.push(visitor);
    }

    public addTheme(theme: Theme)
    {
        for (let visitor of theme.getVisitors()) {
            this.visitors.push(visitor);
        }
    }
}

export class InputChangedEvent extends Event
{
    constructor(
        public resource: object
    ) {
        super('input_changed');
    }
}
