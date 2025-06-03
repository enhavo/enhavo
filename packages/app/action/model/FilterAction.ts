import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {ResourceIndexManager} from "@enhavo/app/manager/ResourceIndexManager"
import {FilterInterface} from "@enhavo/app/filter/FilterInterface";

export class FilterAction extends AbstractAction
{
    public loaded = false;
    public open = false;

    constructor(
        private resourceIndexManager: ResourceIndexManager
    ) {
        super();
    }

    execute(): void
    {
        this.open = !this.open;
    }

    mounted()
    {
        this.resourceIndexManager.onLoaded().then(() => {
            this.loaded = true;
        })
    }

    getFilters()
    {
        return this.resourceIndexManager.filters;
    }

    toggleFilter(filter: FilterInterface)
    {
        filter.active = !filter.active;
    }

    toggleOpen()
    {
        this.open = !this.open;
    }
}
