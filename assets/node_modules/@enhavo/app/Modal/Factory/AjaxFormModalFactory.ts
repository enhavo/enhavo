import AbstractFactory from "@enhavo/app/Modal/Factory/AbstractFactory";
import AjaxFormModal from "@enhavo/app/Modal/Model/AjaxFormModal";

export default class IframeModalFactory extends AbstractFactory
{
    createNew(): AjaxFormModal {
        return new AjaxFormModal(this.application);
    }
}