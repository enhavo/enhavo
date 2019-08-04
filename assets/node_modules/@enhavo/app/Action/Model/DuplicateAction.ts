import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import Confirm from "@enhavo/app/View/Confirm";
import * as URI from "urijs";
import LoadingEvent from "@enhavo/app/ViewStack/Event/LoadingEvent";
import * as $ from "jquery";

export default class DuplicateAction extends AbstractAction
{
    public url: string;
    public token: string;
    public confirmMessage: string;
    public confirmLabelOk: string;
    public confirmLabelCancel: string;

    execute(): void
    {
        this.application.getView().confirm(new Confirm(
            this.confirmMessage,
            () => {
                let uri = new URI(this.url);
                uri = uri.addQuery('view_id', this.application.getView().getId());
                let event = new LoadingEvent(this.application.getView().getId());
                this.application.getEventDispatcher().dispatch(event);
                $('<form method="post" action="'+uri+'"><input type="hidden" name="_csrf_token" value="'+this.token+'"/></form>').appendTo('body').submit();
            },
            () => {

            },
            this.confirmLabelCancel,
            this.confirmLabelOk,
        ));
    }
}