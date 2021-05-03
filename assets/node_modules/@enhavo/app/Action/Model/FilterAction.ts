import FilterManager from "@enhavo/app/Grid/Filter/FilterManager";
import DropdownAction from "@enhavo/app/Action/Model/DropdownAction";
import SingleFilterActionFactory from "@enhavo/app/Action/Factory/SingleFilterActionFactory";

export default class FilterAction extends DropdownAction
{
    private readonly filterManager: FilterManager;
    private readonly singleFilterActionFactory: SingleFilterActionFactory;

    constructor(filterManager: FilterManager, singleFilterActionFactory: SingleFilterActionFactory) {
        super();
        this.filterManager = filterManager;
        this.singleFilterActionFactory = singleFilterActionFactory;
        this.closeAfter = false;
        this.items = [];

        for (let i in this.filterManager.filters) {
            let action = this.singleFilterActionFactory.createNew();
            action.filterKey = this.filterManager.filters[i].key;
            action.label = this.filterManager.filters[i].label;
            action.setActive(this.filterManager.filters[i].active);
            this.items.push(action);
        }
    }
}