import {FilterFactory} from "./FilterFactory";
import {FilterInterface} from "./FilterInterface";

export class FilterManager
{
    constructor(
        private readonly factory: FilterFactory
    ) {
    }

    createFilters(filters: object[]): FilterInterface[]
    {
        let data = [];
        for (let i in filters) {
            if (!filters[i].hasOwnProperty('model')) {
                throw 'The filter data needs a "model" property!';
            }

            data.push(this.factory.createWithData(filters[i]['model'], filters[i]));
        }
        return data;
    }

    public getFilterParameters(filters: FilterInterface[])
    {
        let data = [];
        for(let filter of filters) {
            data.push({
                name: filter.getKey(),
                value: filter.getValue(),
            });
        }
        return data;
    }

    public reset(filters: FilterInterface[])
    {
        for (let filter of filters) {
            filter.reset();
        }
    }

    public setFilterActive(filters: FilterInterface[], filterKey: string, active: boolean)
    {
        for (let filter of filters) {
            if (filter.getKey() === filterKey) {
                filter.setActive(active);
                break;
            }
        }
    }

    public getActiveFilters(filters: FilterInterface[]): FilterInterface[]
    {
        let data = [];
        for (let filter of filters) {
            if (filter.getActive()) {
                data.push(filter);
            }
        }
        return data;
    }
}
