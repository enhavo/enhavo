import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";
import FormAction from "@enhavo/app/action/model/FormAction";
import ModalManager from "@enhavo/app/modal/ModalManager";
import View from "@enhavo/app/view/View";
import Router from "@enhavo/core/Router";
import Translator from "@enhavo/core/Translator";

export default class ModalActionFactory extends AbstractFactory
{
    private readonly view: View;
    private readonly router: Router;
    private readonly modalManager: ModalManager;
    private readonly translator: Translator;

    constructor(view: View, router: Router, modalManager: ModalManager, translator: Translator) {
        super();
        this.view = view;
        this.router = router;
        this.modalManager = modalManager;
        this.translator = translator;
    }

    createNew(): FormAction {
        return new FormAction(this.view, this.router, this.modalManager, this.translator);
    }
}