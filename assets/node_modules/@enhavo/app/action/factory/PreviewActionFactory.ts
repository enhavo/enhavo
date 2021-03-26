import PreviewAction from "@enhavo/app/action/model/PreviewAction";
import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";
import View from "@enhavo/app/view/View";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";

export default class PreviewActionFactory extends AbstractFactory
{
    private readonly view: View;
    private readonly eventDispatcher: EventDispatcher;

    constructor(view: View, eventDispatcher: EventDispatcher) {
        super();
        this.view = view;
        this.eventDispatcher = eventDispatcher;
    }

    createFromData(data: object): PreviewAction
    {
        let action = <PreviewAction>super.createFromData(data);
        action.loadListener();
        return action;
    }

    createNew(): PreviewAction {
        return new PreviewAction(this.view, this.eventDispatcher);
    }
}
