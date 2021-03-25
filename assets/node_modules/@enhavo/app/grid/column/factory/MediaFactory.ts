import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import MediaColumn from "@enhavo/app/Grid/Column/Model/MediaColumn";

export default class TextFactory extends AbstractFactory
{
    createNew(): MediaColumn {
        return new MediaColumn();
    }
}
