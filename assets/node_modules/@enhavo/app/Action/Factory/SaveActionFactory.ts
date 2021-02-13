import SaveAction from "@enhavo/app/Action/Model/SaveAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import View from "@enhavo/app/View/View";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";

export default class SaveActionFactory extends AbstractFactory
{
    private readonly view: View;
    private readonly eventDispatcher: EventDispatcher;

    constructor(view: View, eventDispatcher: EventDispatcher) {
        super();
        this.view = view;
        this.eventDispatcher = eventDispatcher;
    }

    createNew(): SaveAction {
        return new SaveAction(this.view, this.eventDispatcher);
    }
}
