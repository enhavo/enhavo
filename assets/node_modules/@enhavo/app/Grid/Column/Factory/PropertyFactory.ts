import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import PropertyColumn from "@enhavo/app/Grid/Column/Model/PropertyColumn";

export default class PropertyFactory extends AbstractFactory
{
    createFromData(data: object): PropertyColumn
    {
        let column = this.createNew();
        let object = <PropertyColumn>data;
        column.component = object.component;
        return column;
    }

    createNew(): PropertyColumn {
        return new PropertyColumn();
    }
}
