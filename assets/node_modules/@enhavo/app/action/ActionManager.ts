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

    morphActions(targets: ActionInterface[], sources: ActionInterface[]): ActionInterface[]
    {
        for (let target of targets) {
            let found = false;
            for (let source of sources) {
                if (target.key && source.key === target.key) {
                    target.morph(source);
                    found = true;
                    break;
                }
            }
            if (!found) {
                targets.splice(targets.indexOf(target), 1);
            }
        }

        for (let source of sources) {
            let found = false;
            for (let target of targets) {
                if (source.key && source.key === target.key) {
                    found = true;
                    break;
                }
            }
            if (!found) {
                targets.push(source);
            }
        }

        return targets;
    }
}
