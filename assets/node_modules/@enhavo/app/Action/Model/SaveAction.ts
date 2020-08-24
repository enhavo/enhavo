import * as $ from "jquery";
import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import LoadingEvent from "@enhavo/app/ViewStack/Event/LoadingEvent";
import View from "@enhavo/app/View/View";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";

export default class SaveAction extends AbstractAction
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
        let event = new LoadingEvent(this.view.getId());
        this.eventDispatcher.dispatch(event);
        $('form').submit();
    }
}
