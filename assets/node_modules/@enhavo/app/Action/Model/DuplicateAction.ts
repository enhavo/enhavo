import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import Confirm from "@enhavo/app/View/Confirm";
import * as URI from "urijs";
import LoadingEvent from "@enhavo/app/ViewStack/Event/LoadingEvent";
import * as $ from "jquery";
import View from "@enhavo/app/View/View";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";

export default class DuplicateAction extends AbstractAction
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
                let uri = new URI(this.url);
                uri = uri.addQuery('view_id', this.view.getId());
                let event = new LoadingEvent(this.view.getId());
                this.eventDispatcher.dispatch(event);
                $('<form method="post" action="'+uri+'"><input type="hidden" name="_csrf_token" value="'+this.token+'"/></form>').appendTo('body').submit();
            },
            () => {

            },
            this.confirmLabelCancel,
            this.confirmLabelOk,
        ));
    }
}