import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";
import RemoveEvent from "@enhavo/app/ViewStack/Event/RemoveEvent";
import View from "@enhavo/app/ViewStack/View";
import DataLoader from "@enhavo/app/DataLoader";

export default class AbstractViewApp
{
    private data: any;

    constructor(loader: DataLoader, eventDispatcher: EventDispatcher, view: View)
    {
        this.data = loader.load();

        eventDispatcher.on('close', (event: CloseEvent) => {
            if(view.getId() === event.id) {
                event.resolve();
                let id = view.getId();
                eventDispatcher.dispatch(new RemoveEvent(id));
            }
        });
    }

    getData(): any
    {
        return this.data;
    }
}