import $ from "jquery";
import AbstractAction from "@enhavo/app/action/model/AbstractAction";
import LoadingEvent from "@enhavo/app/view-stack/event/LoadingEvent";
import View from "@enhavo/app/view/View";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";

export default class SaveAction extends AbstractAction
{
    public url: string;

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

        let $form = $('form');

        if (this.url) {
            let uri = new URL(this.url, window.origin);
            uri.searchParams.set('view_id', this.view.getId().toString());
            $form.attr('action', uri.toString);
        }
        
        $form.submit();
    }
}
