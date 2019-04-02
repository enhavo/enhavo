import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import LabelColumn from "@enhavo/app/Grid/Column/Model/LabelColumn";

export default class LabelFactory extends AbstractFactory
{
    createFromData(data: object): LabelColumn
    {
        let column = this.createNew();
        let object = <LabelColumn>data;
        column.component = object.component;
        return column;
    }

    createNew(): LabelColumn {
        return new LabelColumn();
    }
}
