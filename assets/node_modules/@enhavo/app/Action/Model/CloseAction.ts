import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";

export default class CloseAction extends AbstractAction
{
    execute(): void
    {
        let id = this.application.getView().getId();
        this.application.getEventDispatcher().dispatch(new CloseEvent(id));
    }
}