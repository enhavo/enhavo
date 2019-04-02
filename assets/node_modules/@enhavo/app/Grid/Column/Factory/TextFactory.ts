import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import TextColumn from "@enhavo/app/Grid/Column/Model/TextColumn";

export default class TextFactory extends AbstractFactory
{
    createFromData(data: object): TextColumn
    {
        let column = this.createNew();
        let object = <TextColumn>data;
        column.component = object.component;
        return column;
    }

    createNew(): TextColumn {
        return new TextColumn();
    }
}
