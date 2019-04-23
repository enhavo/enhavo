import DuplicateAction from "@enhavo/app/Action/Model/DuplicateAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class DropdownActionFactory extends AbstractFactory
{
    createNew(): DuplicateAction {
        return new DuplicateAction(this.application);
    }
}