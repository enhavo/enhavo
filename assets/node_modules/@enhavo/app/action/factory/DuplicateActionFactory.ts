import DuplicateAction from "@enhavo/app/action/model/DuplicateAction";
import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";
import View from "@enhavo/app/view/View";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";

export default class DuplicateActionFactory extends AbstractFactory
{
    private readonly view: View;
    private readonly eventDispatcher: EventDispatcher;

    constructor(view: View, eventDispatcher: EventDispatcher) {
        super();
        this.view = view;
        this.eventDispatcher = eventDispatcher;
    }

    createNew(): DuplicateAction {
        return new DuplicateAction(this.view, this.eventDispatcher);
    }
}
