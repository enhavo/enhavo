import {ActionInterface} from "@enhavo/app/action/ActionInterface";
import {ActionFactory} from "@enhavo/app/action/ActionFactory";

export class ActionManager
{
    constructor(
        private readonly factory: ActionFactory,
    ) {
    }

    createActions(actions: object[]): ActionInterface[]
    {
        let data = [];
        for (let i in actions) {
            data.push(this.createAction(actions[i]));
        }
        return data;
    }

    createAction(action: object): ActionInterface
    {
        if (!action.hasOwnProperty('model')) {
            throw 'The action data needs a "model" property!';
        }

        return this.factory.createWithData(action['model'], action);
    }
}
