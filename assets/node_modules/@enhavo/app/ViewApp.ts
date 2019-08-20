import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";
import DataLoader from "@enhavo/app/DataLoader";
import AppInterface from "@enhavo/app/AppInterface";

export default class ViewApp implements AppInterface
{
    protected data: any;
    protected eventDispatcher: EventDispatcher;
    protected view: View;

    constructor(loader: DataLoader, eventDispatcher: EventDispatcher, view: View)
    {
        this.data = loader.load();
        this.eventDispatcher = eventDispatcher;
        this.view = view;
        this.addCloseListener()
    }

    protected addCloseListener()
    {
        this.view.addDefaultCloseListener();
    }

    getData(): any
    {
        return this.data;
    }
}