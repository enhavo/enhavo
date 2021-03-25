import * as $ from "jquery";
import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class EventAction extends AbstractAction
{
    public event: string;

    execute(): void
    {
        $(document).trigger(this.event);
    }
}