import FilterManager from "@enhavo/app/Grid/Filter/FilterManager";
import ColumnManager from "@enhavo/app/Grid/Column/ColumnManager";
import Router from "@enhavo/core/Router";
import GridConfiguration from "@enhavo/app/Grid/GridConfiguration";
import axios from 'axios';
import * as _ from "lodash";

export default class Grid
{
    private filterManager: FilterManager;
    private columnManager: ColumnManager;
    private router: Router;
    private configuration: GridConfiguration;

    constructor(filterManager: FilterManager, columnManager: ColumnManager, router: Router, configuration: GridConfiguration)
    {
        this.filterManager = filterManager;
        this.columnManager = columnManager;
        this.router = router;

        _.extend(configuration, new GridConfiguration());
        this.configuration = configuration;
    }

    public load()
    {
        this.loadTable();
    }

    public changePage(page: number)
    {
        this.configuration.page = page;
        this.loadTable();
    }

    public changePagination(number: number)
    {
        this.configuration.pagination = number;
        this.loadTable();
    }

    public loadTable()
    {
        this.configuration.loading = true;
        let url = this.router.generate(this.configuration.tableRoute, {
            page: this.configuration.page,
            pagination: this.configuration.pagination
        });
        axios
            .get(url, {params: []})
            // executed on success
            .then(response => {
                this.configuration.rows = response.data.resources;
                this.configuration.count = response.data.pages.count;
                this.configuration.page = response.data.pages.page;
                this.configuration.loading = false;
            })
            // executed on error
            .catch(error => {

            })
            // always executed
            .then(() => {
                //this.loading = false;
            })
    }
}