import DataLoader from '@enhavo/app/DataLoader';
import AbstractViewApp from "@enhavo/app/AbstractViewApp";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";
import UpdatedEvent from "@enhavo/app/ViewStack/Event/UpdatedEvent";

export default class DeleteApp extends AbstractViewApp
{
    constructor(loader: DataLoader, eventDispatcher: EventDispatcher, view: View)
    {
        super(loader, eventDispatcher, view);
        this.eventDispatcher.dispatch(new UpdatedEvent(view.getId()));
    }

    close() {
        this.eventDispatcher.dispatch(new CloseEvent(this.view.getId()));
    }
}