import { AbstractAction } from "@enhavo/app/action/model/AbstractAction";
import View from "@enhavo/app/view/View";
import {FrameEventDispatcher} from "@enhavo/app/frame/FrameEventDispatcher";

export class CloseAction extends AbstractAction
{
    private readonly view: View;
    private readonly eventDispatcher: FrameEventDispatcher;

    constructor(view: View, eventDispatcher: FrameEventDispatcher) {
        super();
        this.view = view;
        this.eventDispatcher = eventDispatcher;
    }

    execute(): void
    {
        let id = this.view.getId();
        this.eventDispatcher.dispatch(new CloseEvent(id));
    }
}