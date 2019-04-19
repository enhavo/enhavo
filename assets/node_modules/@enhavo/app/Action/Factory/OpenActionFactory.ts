import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import OpenAction from "@enhavo/app/Action/Model/OpenAction";
import * as _ from "lodash";

export default class CloseActionFactory extends AbstractFactory
{
    createFromData(data: object): OpenAction
    {
        let action = this.createNew();
        _.extend(data, action);
        return <OpenAction>data;
    }

    createNew(): OpenAction {
        return new OpenAction(this.application)
    }
}