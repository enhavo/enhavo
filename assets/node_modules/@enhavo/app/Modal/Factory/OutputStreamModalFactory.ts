import AbstractFactory from "@enhavo/app/Modal/Factory/AbstractFactory";
import OutputStreamModal from "@enhavo/app/Modal/Model/OutputStreamModal";

export default class OutputStreamModalFactory extends AbstractFactory
{
    createNew(): OutputStreamModal {
        return new OutputStreamModal(this.application);
    }
}