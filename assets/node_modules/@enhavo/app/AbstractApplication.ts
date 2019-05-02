import DataLoader from "@enhavo/app/DataLoader";
import VueLoader from '@enhavo/app/VueLoader';
import View from '@enhavo/app/View/View';
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import AppInterface from "@enhavo/app/AppInterface";
import ApplicationBag from "@enhavo/app/ApplicationBag";
import Router from "@enhavo/core/Router";
import Translator from "@enhavo/core/Translator";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";
import StateManager from "@enhavo/app/State/StateManager";
import ActionManager from "@enhavo/app/Action/ActionManager";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";

export default abstract class AbstractApplication implements ApplicationInterface, ActionAwareApplication
{
    protected dataLoader: DataLoader;
    protected vueLoader: VueLoader;
    protected view: View;
    protected eventDispatcher: EventDispatcher;
    protected app: AppInterface;
    protected router: Router;
    protected translator: Translator;
    protected flashMessenger: FlashMessenger;
    protected stateManager: StateManager;
    protected actionManager: ActionManager;
    protected actionRegistry: ActionRegistry;

    constructor()
    {
        ApplicationBag.setApplication(this);
    }

    abstract getApp(): AppInterface;

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
            this.vueLoader = new VueLoader('app', this.getApp(), this.getEventDispatcher(), this.getView());
        }
        return this.vueLoader;
    }

    public getEventDispatcher(): EventDispatcher
    {
        if(this.eventDispatcher == null) {
            this.eventDispatcher = new EventDispatcher();
        }
        return this.eventDispatcher;
    }

    public getView(): View
    {
        if(this.view == null) {
            this.view = new View(this.getDataLoader().load()['view'], this.getEventDispatcher());
        }
        return this.view;
    }

    public getRouter(): Router
    {
        if(this.router == null) {
            this.router = new Router();
            this.router.setRoutingData((new DataLoader('routes')).load());
        }
        return this.router;
    }

    public getTranslator(): Translator
    {
        if(this.translator == null) {
            this.translator = new Translator();
            this.translator.setData((new DataLoader('translations')).load());
        }
        return this.translator;
    }

    public getFlashMessenger(): FlashMessenger
    {
        if(this.flashMessenger == null) {
            this.flashMessenger = new FlashMessenger(this.getDataLoader().load()['messages']);
        }
        return this.flashMessenger;
    }

    public getActionManager(): ActionManager
    {
        if(this.actionManager == null) {
            this.actionManager = new ActionManager(this.getDataLoader().load().actions, this.getDataLoader().load().actionsSecondary, this.getActionRegistry());
        }
        return this.actionManager;
    }

    public getActionRegistry(): ActionRegistry
    {
        if(this.actionRegistry == null) {
            this.actionRegistry = new ActionRegistry();
            this.actionRegistry.load(this);
        }
        return this.actionRegistry;
    }
}