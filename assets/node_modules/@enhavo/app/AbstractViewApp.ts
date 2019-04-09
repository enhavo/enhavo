import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";
import RemoveEvent from "@enhavo/app/ViewStack/Event/RemoveEvent";
import View from "@enhavo/app/View/View";
import DataLoader from "@enhavo/app/DataLoader";

export default class AbstractViewApp
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
        this.eventDispatcher.on('close', (event: CloseEvent) => {
            if(this.view.getId() === event.id) {
                event.resolve();
                let id = this.view.getId();
                this.eventDispatcher.dispatch(new RemoveEvent(id));
            }
        });
    }

    getData(): any
    {
        return this.data;
    }
}