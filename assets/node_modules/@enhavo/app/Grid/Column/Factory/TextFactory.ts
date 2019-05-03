import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import TextColumn from "@enhavo/app/Grid/Column/Model/TextColumn";

export default class TextFactory extends AbstractFactory
{
    createNew(): TextColumn {
        return new TextColumn();
    }
}
