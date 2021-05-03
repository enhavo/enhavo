import FilterAction from "@enhavo/app/Action/Model/FilterAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import FilterManager from "@enhavo/app/Grid/Filter/FilterManager";
import SingleFilterActionFactory from "@enhavo/app/Action/Factory/SingleFilterActionFactory";

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