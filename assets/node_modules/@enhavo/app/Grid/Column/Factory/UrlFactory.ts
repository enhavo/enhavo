import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import UrlColumn from "@enhavo/app/Grid/Column/Model/UrlColumn";

export default class UrlFactory extends AbstractFactory
{
    createFromData(data: object): UrlColumn
    {
        let column = this.createNew();
        let object = <UrlColumn>data;
        column.component = object.component;
        return column;
    }

    createNew(): UrlColumn {
        return new UrlColumn();
    }
}
