import FilterInterface from "@enhavo/app/grid/filter/FilterInterface";
import FilterRegistry from "@enhavo/app/grid/filter/FilterRegistry";
import * as _ from 'lodash';
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

export default class FilterManager
{
    public filters: FilterInterface[];

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
            let filter = this.registry.getFactory(this.filters[i].component).createFromData(this.filters[i]);
            _.extend(this.filters[i], filter);
        }

        for (let component of this.registry.getComponents()) {
            this.componentRegistry.registerComponent(component.name, component.component)
        }

        this.componentRegistry.registerStore('filterManager', this);
        this.componentRegistry.registerData(this.filters);
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
}
