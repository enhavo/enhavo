import FilterManager from "@enhavo/app/Grid/Filter/FilterManager";


export default class Grid
{
    private filterManager: FilterManager;

    constructor(filterManager: FilterManager)
    {
        this.filterManager = filterManager;
    }

    public load()
    {
        console.log('load');
    }
}