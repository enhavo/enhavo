import {FilterManager} from "@enhavo/app/filter/FilterManager";
import { AbstractAction } from "@enhavo/app/action/model/AbstractAction";

export class SingleFilterAction extends AbstractAction
{
    private readonly filterManager: FilterManager;
    active: boolean;
    filterKey: string;
    icon: string;
    label: string;

    constructor(filterManager: FilterManager) {
        super();
        this.filterManager = filterManager;
        this.component = "single-filter-action";
    }

    execute(): void
    {
        this.active = !this.active;
        this.updateIcon();
        this.filterManager.setFilterActive(this.filterKey, this.active);
    }

    public setActive(active: boolean)
    {
        this.active = active;
        this.updateIcon();
    }

    private updateIcon()
    {
        if (this.active) {
            this.icon = 'remove_circle_outline';
        } else {
            this.icon = 'add_circle_outline';
        }
    }
}
