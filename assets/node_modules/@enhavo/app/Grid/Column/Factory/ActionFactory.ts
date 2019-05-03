import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import ActionColumn from "@enhavo/app/Grid/Column/Model/ActionColumn";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";

export default class ActionFactory extends AbstractFactory
{
    createNew(): ActionColumn {
        return new ActionColumn((<ActionAwareApplication>this.application).getActionRegistry());
    }
}
