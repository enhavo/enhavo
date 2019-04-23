import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import SubColumn from "@enhavo/app/Grid/Column/Model/SubColumn";

export default class UrlFactory extends AbstractFactory
{
    createFromData(data: object): SubColumn
    {
        let column = this.createNew();
        let object = <SubColumn>data;
        column.component = object.component;
        return column;
    }

    createNew(): SubColumn {
        return new SubColumn();
    }
}
