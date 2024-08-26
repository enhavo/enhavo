import { AbstractAction } from "@enhavo/app/action/model/AbstractAction";
import View from "@enhavo/app/view/View";
import {FrameEventDispatcher} from "@enhavo/app/frame/FrameEventDispatcher";
import {ResourceInputManager} from "../../manager/ResourceInputManager";
import {FormUtil} from "@enhavo/vue-form/form/FormUtil";

export class SaveAction extends AbstractAction
{
    public url: string;

    constructor(
        private readonly view: View,
        private readonly eventDispatcher: FrameEventDispatcher,
        private readonly resourceInputManager: ResourceInputManager,
    ) {
        super();
    }

    execute(): void
    {
        let event = new LoadingEvent(this.view.getId());
        this.eventDispatcher.dispatch(event);

        let data = FormUtil.serializeForm(this.resourceInputManager.form);
        
        if (this.url) {
            // let uri = new URL(this.url, window.origin);
            // uri.searchParams.set('view_id', this.view.getId().toString());
            // $form.attr('action', uri.toString);
        }
    }
}
