import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import MediaColumn from "@enhavo/app/Grid/Column/Model/MediaColumn";
import * as _ from "lodash";

export default class TextFactory extends AbstractFactory
{
    createFromData(data: object): MediaColumn
    {
        let column = this.createNew();
        _.extend(data, column);
        return <MediaColumn>data;
    }

    createNew(): MediaColumn {
        return new MediaColumn();
    }
}
