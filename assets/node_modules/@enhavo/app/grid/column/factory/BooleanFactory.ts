import AbstractFactory from "@enhavo/app/grid/column/factory/AbstractFactory";
import BooleanColumn from "@enhavo/app/grid/column/model/BooleanColumn";

export default class BooleanFactory extends AbstractFactory
{
    createNew(): BooleanColumn {
        return new BooleanColumn();
    }
}
