import DeleteAction from "@enhavo/app/Action/Model/DeleteAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import View from "@enhavo/app/View/View";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
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
        return new DeleteAction(this.view, this.eventDispatcher, this.translator);
    }
}