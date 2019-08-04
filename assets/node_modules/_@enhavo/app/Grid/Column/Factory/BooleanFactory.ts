import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import BooleanColumn from "@enhavo/app/Grid/Column/Model/BooleanColumn";

export default class BooleanFactory extends AbstractFactory
{
    createNew(): BooleanColumn {
        return new BooleanColumn();
    }
}
