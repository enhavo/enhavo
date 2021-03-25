import AbstractFactory from "@enhavo/app/Modal/Factory/AbstractFactory";
import AjaxFormModal from "@enhavo/app/Modal/Model/AjaxFormModal";
import Router from "@enhavo/core/Router";
import ModalManager from "@enhavo/app/Modal/ModalManager";

export default class IframeModalFactory extends AbstractFactory
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