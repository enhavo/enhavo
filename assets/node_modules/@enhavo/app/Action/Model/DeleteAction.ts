import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import Confirm from "@enhavo/app/View/Confirm";
import * as $ from "jquery";
import LoadingEvent from "@enhavo/app/ViewStack/Event/LoadingEvent";
import * as URI from 'urijs';
export default class DeleteAction extends AbstractAction
{
    public url: string;
    public token: string;

    execute(): void
    {
        this.application.getView().confirm(new Confirm(
            this.application.getTranslator().trans('enhavo_app.delete.message.question'),
            () => {
                let uri = new URI(this.url);
                uri = uri.addQuery('view_id', this.application.getView().getId());
                let event = new LoadingEvent(this.application.getView().getId());
                this.application.getEventDispatcher().dispatch(event);
                $('<form method="post" action="'+uri+'"><input type="hidden" name="_csrf_token" value="'+this.token+'"/></form>').appendTo('body').submit();
            },
            () => {

            },
            this.application.getTranslator().trans('enhavo_app.delete.label.cancel'),
            this.application.getTranslator().trans('enhavo_app.delete.label.delete'),
        ));
    }
}