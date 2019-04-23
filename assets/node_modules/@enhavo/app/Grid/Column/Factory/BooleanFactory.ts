import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import BooleanColumn from "@enhavo/app/Grid/Column/Model/BooleanColumn";

export default class BooleanFactory extends AbstractFactory
{
    createFromData(data: object): BooleanColumn
    {
        let column = this.createNew();
        let object = <BooleanColumn>data;
        column.component = object.component;
        return column;
    }

    createNew(): BooleanColumn {
        return new BooleanColumn();
    }
}
