import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";
import View from "@enhavo/app/View/View";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";

export default class CloseAction extends AbstractAction
{
    private readonly view: View;
    private readonly eventDispatcher: EventDispatcher;

    constructor(view: View, eventDispatcher: EventDispatcher) {
        super();
        this.view = view;
        this.eventDispatcher = eventDispatcher;
    }

    execute(): void
    {
        let id = this.view.getId();
        this.eventDispatcher.dispatch(new CloseEvent(id));
    }
}