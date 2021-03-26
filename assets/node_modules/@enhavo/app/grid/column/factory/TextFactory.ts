import AbstractFactory from "@enhavo/app/grid/column/factory/AbstractFactory";
import TextColumn from "@enhavo/app/grid/column/model/TextColumn";

export default class TextFactory extends AbstractFactory
{
    createNew(): TextColumn {
        return new TextColumn();
    }
}
