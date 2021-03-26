import AbstractFactory from "@enhavo/app/grid/column/factory/AbstractFactory";
import StateColumn from "@enhavo/app/grid/column/model/StateColumn";

export default class StateFactory extends AbstractFactory
{
    createNew(): StateColumn {
        return new StateColumn();
    }
}
