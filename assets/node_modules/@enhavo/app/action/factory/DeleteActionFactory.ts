import DeleteAction from "@enhavo/app/action/model/DeleteAction";
import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";
import View from "@enhavo/app/view/View";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import Translator from "@enhavo/core/Translator";

export default class DeleteActionFactory extends AbstractFactory
{
    private readonly view: View;
    private readonly eventDispatcher: EventDispatcher;
    private readonly translator: Translator;

    constructor(view: View, eventDispatcher: EventDispatcher, translator: Translator) {
        super();
        this.view = view;
        this.eventDispatcher = eventDispatcher;
        this.translator = translator;
    }

    createNew(): DeleteAction {
        return new DeleteAction(this.view, this.eventDispatcher);
    }
}
