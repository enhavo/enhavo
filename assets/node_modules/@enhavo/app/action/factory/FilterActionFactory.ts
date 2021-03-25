import FilterAction from "@enhavo/app/action/model/FilterAction";
import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";

export default class FilterActionFactory extends AbstractFactory
{
    createNew(): FilterAction {
        return new FilterAction();
    }
}