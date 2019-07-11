import AbstractFactory from "@enhavo/app/Modal/Factory/AbstractFactory";
import IframeModal from "@enhavo/app/Modal/Model/IframeModal";

export default class IframeModalFactory extends AbstractFactory
{
    createNew(): IframeModal {
        let modal = new IframeModal(this.application);
        modal.component = 'iframe-modal';
        return modal;
    }
}