import SaveAction from "@enhavo/app/Action/Model/SaveAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class SaveActionFactory extends AbstractFactory
{
    createFromData(data: object): SaveAction
    {
        let action = this.createNew();
        let object = <SaveAction>data;
        action.component = object.component;
        return action;
    }

    createNew(): SaveAction {
        return new SaveAction(this.application);
    }
}