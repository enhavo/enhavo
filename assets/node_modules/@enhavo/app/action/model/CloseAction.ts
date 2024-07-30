import { AbstractAction } from "@enhavo/app/action/model/AbstractAction";
import CloseEvent from "@enhavo/app/view-stack/event/CloseEvent";
import View from "@enhavo/app/view/View";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";

export class CloseAction extends AbstractAction
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