import SaveAction from "@enhavo/app/action/model/SaveAction";
import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";
import View from "@enhavo/app/view/View";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";

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
