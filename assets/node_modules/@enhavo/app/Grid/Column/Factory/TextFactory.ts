import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import TextColumn from "@enhavo/app/Grid/Column/Model/TextColumn";
import * as _ from "lodash";

export default class TextFactory extends AbstractFactory
{
    createFromData(data: object): TextColumn
    {
        let column = this.createNew();
        _.extend(data, column);
        return <TextColumn>data;
    }

    createNew(): TextColumn {
        return new TextColumn();
    }
}
