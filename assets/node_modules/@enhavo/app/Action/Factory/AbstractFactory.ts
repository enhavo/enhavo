import * as _ from 'lodash';
import ActionInterface from "@enhavo/app/Action/ActionInterface";

export default abstract class AbstractFactory
{
    createFromData(data: object): ActionInterface
    {
        let action = this.createNew();
        action = _.extend(data, action);
        return action;
    }

    abstract createNew(): ActionInterface;
}
