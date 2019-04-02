import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import MultiplePropertyColumn from "@enhavo/app/Grid/Column/Model/MultiplePropertyColumn";

export default class AutoCompleteEntityFactory extends AbstractFactory
{
    createFromData(data: object): MultiplePropertyColumn
    {
        let column = this.createNew();
        let object = <MultiplePropertyColumn>data;
        column.component = object.component;
        return column;
    }

    createNew(): MultiplePropertyColumn {
        return new MultiplePropertyColumn();
    }
}
