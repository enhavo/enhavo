import * as $ from "jquery";
import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import LoadingEvent from "@enhavo/app/ViewStack/Event/LoadingEvent";

export default class SaveAction extends AbstractAction
{
    execute(): void
    {
        let event = new LoadingEvent(this.application.getView().getId());
        this.application.getEventDispatcher().dispatch(event);
        $('form').submit();
    }
}