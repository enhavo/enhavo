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

export class ResourceInputManager
{
    public loading: boolean = false;
    public actions: ActionInterface[];
    public actionsSecondary: ActionInterface[];
    public tabs: TabInterface[];
    public routes: RouteContainer;
    public form: Form;

    private visitors: FormVisitorInterface[];

    constructor(
        private router: Router,
        private actionManager: ActionManager,
        private tabManager: TabManager,
        private formFactory: FormFactory,
    ) {
    }

    async load(route: string)
    {
        this.loading = true;

        let url = this.router.generate(route);

        const response = await fetch(url);
        const data = await response.json();

        this.actions = this.actionManager.createActions(data.actions);
        this.actionsSecondary = this.actionManager.createActions(data.actionsSecondary);
        this.tabs = this.tabManager.createTabs(data.tabs);
        this.routes = new RouteContainer(data.routes);
        this.form = this.formFactory.create(data.form, this.visitors);

        this.loading = false;

        this.selectFirstTab()
    }

    async save(route: string)
    {
        this.loading = true;

        let url = this.router.generate(route);

        const formData = FormUtil.serializeForm(this.form);
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
        });
        const data = await response.json();

        this.actions = this.actionManager.createActions(data.actions);
        this.actionsSecondary = this.actionManager.createActions(data.actionsSecondary);
        this.routes = new RouteContainer(data.routes);
        this.form.destroy();
        this.form = this.formFactory.create(data.form, this.visitors);

        this.loading = false;
    }

    async autoSave(route: string)
    {
        let url = this.router.generate(route);

        const formData = FormUtil.serializeForm(this.form);
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
        });
        const data = await response.json();

        this.actions = this.actionManager.createActions(data.actions);
        this.actionsSecondary = this.actionManager.createActions(data.actionsSecondary);
    }

    public selectTab(tab: TabInterface)
    {
        for (let loopTab of this.tabs) {
            loopTab.active = loopTab === tab;
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
