import CloseAction from "@enhavo/app/action/model/CloseAction";
import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";
import View from "@enhavo/app/view/View";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";

export default class CloseActionFactory extends AbstractFactory
{
    private readonly view: View;
    private readonly eventDispatcher: EventDispatcher;

    constructor(view: View, eventDispatcher: EventDispatcher) {
        super();
        this.view = view;
        this.eventDispatcher = eventDispatcher;
    }

    createNew(): CloseAction {
        return new CloseAction(this.view, this.eventDispatcher);
    }
}