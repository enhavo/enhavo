import {Router} from "@enhavo/app/routing/Router";
import {ActionManager} from "@enhavo/app/action/ActionManager";
import {ActionInterface} from "@enhavo/app/action/ActionInterface";
import {RouteContainer} from "../routing/RouteContainer";
import {TabManager} from "../tab/TabManager";
import {TabInterface} from "../tab/TabInterface";
import {Form} from "@enhavo/vue-form/model/Form";
import {FormFactory} from "@enhavo/vue-form/form/FormFactory";
import {FormUtil} from "@enhavo/vue-form/form/FormUtil";
import {FormVisitorInterface} from "@enhavo/vue-form/form/FormVisitor";
import {Theme} from "@enhavo/vue-form/form/Theme";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {VueRouterFactory} from "@enhavo/app/vue/VueRouterFactory";
import {FormEventDispatcher} from "@enhavo/vue-form/form/FormEventDispatcher";

export class ResourceInputManager
{
    public actions: ActionInterface[];
    public actionsSecondary: ActionInterface[];
    public tabs: TabInterface[] = null;
    public routes: RouteContainer;
    public form: Form;

    private visitors: FormVisitorInterface[] = [];
    private loadedPromiseResolveCalls: Array<() => void> = [];
    private loaded: boolean = false;

    constructor(
        private router: Router,
        private actionManager: ActionManager,
        private tabManager: TabManager,
        private formFactory: FormFactory,
        private frameManager: FrameManager,
        private vueRouterFactory: VueRouterFactory,
        private eventDispatcher: FormEventDispatcher,
    ) {
    }

    async load(route: string, id: number = null)
    {
        let parameters = {};

        if (id) {
            parameters['id'] = id;
        }

        let url = this.router.generate(route, parameters);

        const response = await fetch(url);
        const data = await response.json();

        this.routes = new RouteContainer(data.routes);
        this.form = this.formFactory.create(data.form, this.visitors);
        this.tabs = this.tabManager.createTabs(data.tabs);

        this.actions = this.actionManager.createActions(data.actions);
        this.actionsSecondary = this.actionManager.createActions(data.actionsSecondary);

        this.initTab(window.location.href);

        this.frameManager.loaded();
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

    async save(url: string, morph: boolean  = false)
    {
        this.form.morphStart();

        const response = await this.sendForm(url);
        const data = await response.json();

        this.routes = new RouteContainer(data.routes);

        if (morph) {
            let newForm = this.formFactory.create(data.form, this.visitors);
            this.form.morphMerge(newForm);
            this.form.morphFinish();
        } else {
            this.form.destroy();
            this.form = this.formFactory.create(data.form, this.visitors);
        }

        if (morph) {
            this.actionManager.morphActions(this.actions, this.actionManager.createActions(data.actions));
            this.actionManager.morphActions(this.actionsSecondary, this.actionManager.createActions(data.actionsSecondary));
        } else {
            this.actions = this.actionManager.createActions(data.actions);
            this.actionsSecondary = this.actionManager.createActions(data.actionsSecondary);
        }

        if (data.redirect) {
            await this.vueRouterFactory.getRouter().push({
                path: data.redirect,
                query: {tab: this.getActiveTab().key}
            });

            this.frameManager.save();
        }
    }

    async sendForm(url: string, signal: AbortSignal = null): Promise<Response>
    {
        const formData = FormUtil.serializeForm(this.form);
        return await fetch(url, {
            method: 'POST',
            body: formData,
            signal: signal
        });
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
