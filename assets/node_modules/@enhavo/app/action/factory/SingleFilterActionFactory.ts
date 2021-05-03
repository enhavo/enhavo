import SingleFilterAction from "@enhavo/app/action/model/SingleFilterAction";
import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";
import FilterManager from "@enhavo/app/grid/filter/FilterManager";

export default class SingleFilterActionFactory extends AbstractFactory
{
    private readonly filterManager: FilterManager;

    constructor(filterManager: FilterManager) {
        super();
        this.filterManager = filterManager;
    }

    createNew(): SingleFilterAction {
        return new SingleFilterAction(this.filterManager);
    }
}
