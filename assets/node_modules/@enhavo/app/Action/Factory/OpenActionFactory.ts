import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import OpenAction from "@enhavo/app/Action/Model/OpenAction";

export default class OpenActionFactory extends AbstractFactory
{
    createNew(): OpenAction {
        return new OpenAction(this.application)
    }
}