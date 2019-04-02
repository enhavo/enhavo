import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import TemplateColumn from "@enhavo/app/Grid/Column/Model/TemplateColumn";

export default class TemplateFactory extends AbstractFactory
{
    createFromData(data: object): TemplateColumn
    {
        let column = this.createNew();
        let object = <TemplateColumn>data;
        column.component = object.component;
        return column;
    }

    createNew(): TemplateColumn {
        return new TemplateColumn();
    }
}
