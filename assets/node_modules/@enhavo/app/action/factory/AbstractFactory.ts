import * as _ from 'lodash';
import ActionInterface from "@enhavo/app/action/ActionInterface";

export default abstract class AbstractFactory
{
    createFromData(data: object): ActionInterface
    {
        let action = this.createNew();
        action = _.extend(action, data);
        return action;
    }

    abstract createNew(): ActionInterface;
}
