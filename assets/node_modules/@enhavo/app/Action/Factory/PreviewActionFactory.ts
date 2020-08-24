import PreviewAction from "@enhavo/app/Action/Model/PreviewAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import View from "@enhavo/app/View/View";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";

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
