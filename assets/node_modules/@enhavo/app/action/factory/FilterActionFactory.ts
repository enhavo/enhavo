import FilterAction from "@enhavo/app/action/model/FilterAction";
import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";
import FilterManager from "@enhavo/app/grid/filter/FilterManager";
import SingleFilterActionFactory from "@enhavo/app/action/factory/SingleFilterActionFactory";

export default class FilterActionFactory extends AbstractFactory
{
    private readonly filterManager: FilterManager;
    private readonly singleFilterActionFactory: SingleFilterActionFactory;

    constructor(filterManager: FilterManager, singleFilterActionFactory: SingleFilterActionFactory) {
        super();
        this.filterManager = filterManager;
        this.singleFilterActionFactory = singleFilterActionFactory;
    }

    createNew(): FilterAction {
        return new FilterAction(this.filterManager, this.singleFilterActionFactory);
    }
}