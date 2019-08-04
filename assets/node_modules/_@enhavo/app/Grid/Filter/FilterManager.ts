import FilterInterface from "@enhavo/app/Grid/Filter/FilterInterface";
import FilterRegistry from "@enhavo/app/Grid/Filter/FilterRegistry";
import * as _ from 'lodash';

export default class FilterManager
{
    private filters: FilterInterface[];
    private registry: FilterRegistry;

    constructor(filters: FilterInterface[], registry:FilterRegistry)
    {
        this.registry = registry;
        for (let i in filters) {
            let filter = registry.getFactory(filters[i].component).createFromData(filters[i]);
            _.extend(filters[i], filter);
        }
        this.filters = filters;
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