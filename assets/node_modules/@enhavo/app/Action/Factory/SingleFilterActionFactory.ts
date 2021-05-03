import SingleFilterAction from "@enhavo/app/Action/Model/SingleFilterAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import FilterManager from "@enhavo/app/Grid/Filter/FilterManager";

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
