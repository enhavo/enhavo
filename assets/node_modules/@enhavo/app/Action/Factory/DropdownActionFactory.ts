import ActionInterface from "@enhavo/app/Action/ActionInterface";
import DropdownAction from "@enhavo/app/Action/Model/DropdownAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import ActionManager from "@enhavo/app/Action/ActionManager";
import * as _ from "lodash";

export default class DropdownActionFactory extends AbstractFactory
{
    private readonly actionManager: ActionManager;

    constructor(actionManager: ActionManager) {
        super();
        this.actionManager = actionManager;
    }

    createNew(): DropdownAction {
        return new DropdownAction();
    }

    createFromData(data: object): ActionInterface {
        let action = this.createNew();
        action = _.extend(data, action);
        this.actionManager.initializeActions(action.items);
        return action;
    }
}