import { AbstractAction } from "@enhavo/app/action/model/AbstractAction";
import Confirm from "@enhavo/app/ui/Confirm";
import $ from "jquery";
import LoadingEvent from "@enhavo/app/frame/event/LoadingEvent";
import EventDispatcher from "@enhavo/app/frame/EventDispatcher";
import View from "@enhavo/app/view/View";

export class DeleteAction extends AbstractAction
{
    public url: string;
    public token: string;


    public confirmMessage: string;
    public confirmLabelOk: string;
    public confirmLabelCancel: string;

    private readonly view: View;
    private readonly eventDispatcher: EventDispatcher;

    constructor(view: View, eventDispatcher: EventDispatcher) {
        super();
        this.view = view;
        this.eventDispatcher = eventDispatcher;
    }

    execute(): void
    {
        this.view.confirm(new Confirm(
            this.confirmMessage,
            () => {
                let uri = new URL(this.url, window.origin);
                uri.searchParams.set('view_id', this.view.getId().toString());
                let event = new LoadingEvent(this.view.getId());
                this.eventDispatcher.dispatch(event);
                $('<form method="post" action="'+uri.toString()+'"><input type="hidden" name="_csrf_token" value="'+this.token+'"/></form>').appendTo('body').submit();
            },
            () => {

            },
            this.confirmLabelCancel,
            this.confirmLabelOk,
        ));
    }
}
