import FilterAction from "@enhavo/app/Action/Model/FilterAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class FilterActionFactory extends AbstractFactory
{
    createNew(): FilterAction {
        return new FilterAction();
    }
}