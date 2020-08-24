import CloseAction from "@enhavo/app/Action/Model/CloseAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import View from "@enhavo/app/View/View";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";

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