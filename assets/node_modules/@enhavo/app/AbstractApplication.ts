import DataLoader from "@enhavo/app/DataLoader";
import VueLoader from '@enhavo/app/VueLoader';
import View from '@enhavo/app/ViewStack/View';
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import AppInterface from "@enhavo/app/AppInterface";
import ApplicationBag from "@enhavo/app/ApplicationBag";
import Router from "@enhavo/core/Router";

export default abstract class AbstractApplication implements ApplicationInterface
{
    protected dataLoader: DataLoader;
    protected vueLoader: VueLoader;
    protected view: View;
    protected eventDispatcher: EventDispatcher;
    protected app: AppInterface;
    protected router: Router;

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
            this.eventDispatcher = new EventDispatcher(this.getView());
        }
        return this.eventDispatcher;
    }

    public getView(): View
    {
        if(this.view == null) {
            this.view = new View();
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
}