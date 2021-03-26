import AbstractFactory from "@enhavo/app/grid/column/factory/AbstractFactory";
import MediaColumn from "@enhavo/app/grid/column/model/MediaColumn";

export default class TextFactory extends AbstractFactory
{
    createNew(): MediaColumn {
        return new MediaColumn();
    }
}
