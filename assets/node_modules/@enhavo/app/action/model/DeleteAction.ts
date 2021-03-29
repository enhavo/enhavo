import AbstractAction from "@enhavo/app/action/model/AbstractAction";
import Confirm from "@enhavo/app/view/Confirm";
import * as $ from "jquery";
import LoadingEvent from "@enhavo/app/view-stack/event/LoadingEvent";
import * as URI from 'urijs';
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import View from "@enhavo/app/view/View";
import Translator from "@enhavo/core/Translator";

export default class DeleteAction extends AbstractAction
{
    public url: string;
    public token: string;

    private readonly view: View;
    private readonly eventDispatcher: EventDispatcher;
    private readonly translator: Translator;

    constructor(view: View, eventDispatcher: EventDispatcher, translator: Translator) {
        super();
        this.view = view;
        this.eventDispatcher = eventDispatcher;
        this.translator = translator;
    }

    execute(): void
    {
        this.view.confirm(new Confirm(
            this.translator.trans('enhavo_app.delete.message.question'),
            () => {
                let uri = new URI(this.url);
                uri = uri.addQuery('view_id', this.view.getId());
                let event = new LoadingEvent(this.view.getId());
                this.eventDispatcher.dispatch(event);
                $('<form method="post" action="'+uri+'"><input type="hidden" name="_csrf_token" value="'+this.token+'"/></form>').appendTo('body').submit();
            },
            () => {

            },
            this.translator.trans('enhavo_app.delete.label.cancel'),
            this.translator.trans('enhavo_app.delete.label.delete'),
        ));
    }
}