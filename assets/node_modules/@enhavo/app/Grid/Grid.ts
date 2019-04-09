import FilterManager from "@enhavo/app/Grid/Filter/FilterManager";
import ColumnManager from "@enhavo/app/Grid/Column/ColumnManager";
import RowData from "@enhavo/app/Grid/Column/RowData";
import ColumnInterface from "@enhavo/app/Grid/Column/ColumnInterface";
import Router from "@enhavo/core/Router";
import GridConfiguration from "@enhavo/app/Grid/GridConfiguration";
import axios from 'axios';
import * as _ from "lodash";
import BatchManager from "@enhavo/app/Grid/Batch/BatchManager";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import CreateEvent from "@enhavo/app/ViewStack/Event/CreateEvent";
import View from "@enhavo/app/View/View";
import ViewInterface from "@enhavo/app/ViewStack/ViewInterface";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";
import RemovedEvent from "@enhavo/app/ViewStack/Event/RemovedEvent";
import UpdatedEvent from "@enhavo/app/ViewStack/Event/UpdatedEvent";

export default class Grid
{
    private filterManager: FilterManager;
    private columnManager: ColumnManager;
    private router: Router;
    private eventDispatcher: EventDispatcher;
    private configuration: GridConfiguration;
    private view: View;

    constructor(
        filterManager: FilterManager,
        columnManager: ColumnManager,
        batchManager: BatchManager,
        router: Router,
        eventDispatcher: EventDispatcher,
        configuration: GridConfiguration,
        view: View
    ) {
        this.filterManager = filterManager;
        this.columnManager = columnManager;
        this.router = router;
        this.eventDispatcher = eventDispatcher;
        this.view = view;

        _.extend(configuration, new GridConfiguration());
        this.configuration = configuration;
        this.initListener();
    }

    private initListener()
    {
        this.eventDispatcher.on('removed', (event: RemovedEvent) => {
            if(event.id == this.configuration.editView) {
                this.configuration.editView = null;
            }
        });

        this.eventDispatcher.on('updated', (event: UpdatedEvent) => {
            if(event.id == this.configuration.editView) {
                this.loadTable();
            }
        });
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
        this.configuration.page = 1;
        this.configuration.pagination = number;
        this.loadTable();
    }

    public changeSelect(row: RowData, value: boolean)
    {
        row.selected = value;
    }

    public changeSelectAll(value: boolean)
    {
        this.configuration.selectAll = value;
        for(let row of this.configuration.rows) {
            row.selected = value;
        }
    }

    public changeBatch(value: string)
    {
        this.configuration.batch = value;
    }

    public changeEditView(id: number|null)
    {
        this.configuration.editView = id;
    }

    public open(row: RowData)
    {
        if(this.configuration.editView != null) {
            this.eventDispatcher.dispatch(new CloseEvent(this.configuration.editView))
                .then(() => {
                    this.openView(row);
                })
                .catch(() => {})
            ;
        } else {
            this.openView(row);
        }
    }

    protected openView(row: RowData)
    {
        let url = this.router.generate(this.configuration.updateRoute, {
            id: row.id
        });

        this.eventDispatcher.dispatch(new CreateEvent({
            label: 'table',
            component: 'iframe-view',
            url: url
        }, this.view.getId())).then((view: ViewInterface) => {
            this.configuration.editView = view.id;
        }).catch(() => {});
    }

    public setEditView(id: number)
    {
        this.configuration.editView = id;
    }

    public getEditView(): number
    {
        return this.configuration.editView;
    }

    public executeBatch()
    {
        // sendRequest(): void {
        //     if(this.hasSelection) {
        //         let currentBatch = this.actions.find(
        //             action => action['key'] === this.value
        //         );
        //         let batchUri = currentBatch['uri'];
        //
        //         axios.post(batchUri, this.selected)
        //         // executed on success
        //         .then(response => {
        //         })
        //         // executed on error
        //         .catch(error => {
        //         })
        //         // always executed
        //         .then(() => {
        //             this.clearSelection();
        //         })
        //     }
        // }
    }

    public changeSortDirection(column: ColumnInterface)
    {
        if(column.sortable) {
            for(let otherColumns of this.configuration.columns) {
                if(otherColumns != column) {
                    otherColumns.directionDesc = null;
                }
            }

            if(column.directionDesc === false) {
                column.directionDesc = null
            } else {
                column.directionDesc = !column.directionDesc;
            }

            this.loadTable();
        }
    }

    public loadTable()
    {
        this.configuration.selectAll = false;
        this.configuration.loading = true;
        let url = this.router.generate(this.configuration.tableRoute, {
            page: this.configuration.page,
            pagination: this.configuration.pagination
        });
        axios
            .get(url, {params: []})
            // executed on success
            .then(response => {
                this.configuration.rows = this.createRowData(response.data.resources);
                this.configuration.count = parseInt(response.data.pages.count);
                this.configuration.page = parseInt(response.data.pages.page);
                this.configuration.loading = false;
            })
            // executed on error
            .catch(error => {

            })
            // always executed
            .then(() => {
                this.configuration.loading = false;
            })
    }

    private createRowData(objects: object[]): RowData[]
    {
        let data = [];
        for(let row of objects) {
            let rowData = new RowData();
            data.push(_.extend(rowData, row));
        }
        return data;
    }
}