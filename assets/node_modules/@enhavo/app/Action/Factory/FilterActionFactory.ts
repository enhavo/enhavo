import FilterAction from "@enhavo/app/Action/Model/FilterAction";

export default class FilterActionFactory
{
    createFromData(data: object): FilterAction
    {
        let action = this.createNew();
        let object = <FilterAction>data;
        action.component = object.component;
        return action;
    }

    createNew(): FilterAction {
        return new FilterAction()
    }
}