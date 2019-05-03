import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import SubColumn from "@enhavo/app/Grid/Column/Model/SubColumn";

export default class UrlFactory extends AbstractFactory
{
    createNew(): SubColumn {
        return new SubColumn();
    }
}
