import AbstractFactory from "@enhavo/app/Modal/Factory/AbstractFactory";
import IframeModal from "@enhavo/app/Modal/Model/IframeModal";

export default class IframeModalFactory extends AbstractFactory
{
    createNew(): IframeModal {
        return new IframeModal(this.application);
    }
}