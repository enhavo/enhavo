import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import ListColumn from "@enhavo/app/Grid/Column/Model/ListColumn";

export default class ListFactory extends AbstractFactory
{
    createFromData(data: object): ListColumn
    {
        let column = this.createNew();
        let object = <ListColumn>data;
        column.component = object.component;
        return column;
    }

    createNew(): ListColumn {
        return new ListColumn();
    }
}
