import AbstractFactory from "@enhavo/app/modal/factory/AbstractFactory";
import AjaxFormModal from "@enhavo/app/modal/model/AjaxFormModal";
import Router from "@enhavo/core/Router";
import ModalManager from "@enhavo/app/modal/ModalManager";

export default class AjaxFormModalFactory extends AbstractFactory
{
    private readonly modalManager: ModalManager;
    private readonly router: Router;

    constructor(modalManager: ModalManager, router: Router) {
        super();
        this.modalManager = modalManager;
        this.router = router;
    }

    createNew(): AjaxFormModal {
        return new AjaxFormModal(this.modalManager, this.router);
    }
}