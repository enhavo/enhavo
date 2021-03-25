import AbstractFactory from "@enhavo/app/grid/column/factory/AbstractFactory";
import SubColumn from "@enhavo/app/grid/column/model/SubColumn";

export default class UrlFactory extends AbstractFactory
{
    createNew(): SubColumn {
        return new SubColumn();
    }
}
