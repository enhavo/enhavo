import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import PositionColumn from "@enhavo/app/Grid/Column/Model/PositionColumn";

export default class PositionFactory extends AbstractFactory
{
    createFromData(data: object): PositionColumn
    {
        let column = this.createNew();
        let object = <PositionColumn>data;
        column.component = object.component;
        return column;
    }

    createNew(): PositionColumn {
        return new PositionColumn();
    }
}
