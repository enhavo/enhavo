import Form from "@enhavo/app/Form/Form";
import DataLoader from "@enhavo/app/DataLoader";
import VueLoader from '@enhavo/app/VueLoader';
import Component from '@enhavo/app/Form/Components/FormComponent.vue';
import dispatcher from '@enhavo/app/ViewStack/dispatcher';
import View from '@enhavo/app/ViewStack/View';
import ActionManager from "@enhavo/app/Action/ActionManager";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";

export class Application
{
    protected static application: Application;
    protected form: Form = null;
    protected dataLoader: DataLoader;
    protected vueLoader: VueLoader;
    protected view: View;
    protected actionManager: ActionManager;

    private constructor() {}

    public static create()
    {
        Application.application = new Application();
        return Application.application;
    }


    public getApplication(): Application
    {
        return Application.application;
    }

    public getForm(): Form
    {
        if(this.form == null) {
            this.form = new Form(this.getDataLoader(), this.getActionManager());
        }
        return this.form;
    }

    public getDataLoader(): DataLoader
    {
        if(this.dataLoader == null) {
            this.dataLoader = new DataLoader('data');
        }
        return this.dataLoader;
    }

    public getVueLoader(): VueLoader
    {
        if(this.vueLoader == null) {
            this.vueLoader = new VueLoader('app', this.getForm(), Component);
        }
        return this.vueLoader;
    }

    public getDispatcher(): EventDispatcher
    {
        return dispatcher;
    }

    public getView(): View
    {
        if(this.view == null) {
            this.view = new View(this.getDispatcher());
        }
        return this.view;
    }

    public getActionManager(): ActionManager
    {
        if(this.actionManager == null) {
            this.actionManager = new ActionManager(this.getDataLoader().load().actions);
        }
        return this.actionManager;
    }
}

export default Application.create();