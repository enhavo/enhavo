import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import StateColumn from "@enhavo/app/Grid/Column/Model/StateColumn";

export default class StateFactory extends AbstractFactory
{
    createNew(): StateColumn {
        return new StateColumn();
    }
}
