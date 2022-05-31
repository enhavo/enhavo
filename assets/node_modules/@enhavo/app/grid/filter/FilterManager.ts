import FilterInterface from "@enhavo/app/grid/filter/FilterInterface";
import FilterRegistry from "@enhavo/app/grid/filter/FilterRegistry";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

export default class FilterManager
{
    public filters: FilterInterface[];
    public activeFilters: FilterInterface[] = [];

    private readonly registry: FilterRegistry;
    private readonly componentRegistry: ComponentRegistryInterface;

    constructor(filters: FilterInterface[], registry: FilterRegistry, componentRegistry: ComponentRegistryInterface)
    {
        this.filters = filters;
        this.registry = registry;
        this.componentRegistry = componentRegistry;
    }

    public init()
    {
        for (let i in this.filters) {
            this.filters[i] = this.registry.getFactory(this.filters[i].component).createFromData(this.filters[i]);
        }

        this.filters = this.componentRegistry.registerData(this.filters);
        this.activeFilters = this.componentRegistry.registerData(this.activeFilters);

        this.updateActiveFilters();

        for (let component of this.registry.getComponents()) {
            this.componentRegistry.registerComponent(component.name, component.component)
        }

        this.componentRegistry.registerStore('filterManager', this);
    }

    public getFilterParameters()
    {
        let data = [];
        for(let filter of this.filters) {
            data.push({
                name: filter.getKey(),
                value: filter.getValue(),
            });
        }
        return data;
    }

    public reset()
    {
        for(let filter of this.filters) {
            filter.reset();
        }
    }

    public setFilterActive(filterKey: string, active: boolean)
    {
        for (let i in this.filters) {
            if (this.filters[i].getKey() === filterKey) {
                this.filters[i].setActive(active);
                break;
            }
        }
        this.updateActiveFilters();
    }

    private updateActiveFilters()
    {
        this.activeFilters.splice(0, this.activeFilters.length);
        for (let i in this.filters) {
            if (this.filters[i].getActive()) {
                this.activeFilters.push(this.filters[i]);
            }
        }
    }

}
