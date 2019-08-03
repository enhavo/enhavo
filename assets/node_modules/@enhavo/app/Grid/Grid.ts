import FilterManager from "@enhavo/app/Grid/Filter/FilterManager";
import ColumnManager from "@enhavo/app/Grid/Column/ColumnManager";
import RowData from "@enhavo/app/Grid/Column/RowData";
import ColumnInterface from "@enhavo/app/Grid/Column/ColumnInterface";
import Router from "@enhavo/core/Router";
import GridConfiguration from "@enhavo/app/Grid/GridConfiguration";
import axios, {CancelTokenSource} from 'axios';
import * as _ from "lodash";
import * as $ from "jquery";
import BatchManager from "@enhavo/app/Grid/Batch/BatchManager";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";
import UpdatedEvent from "@enhavo/app/ViewStack/Event/UpdatedEvent";
import Translator from "@enhavo/core/Translator";
import Confirm from "@enhavo/app/View/Confirm";
import Batch from "@enhavo/app/Grid/Batch/Batch";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";
import Message from "@enhavo/app/FlashMessage/Message";
import * as jexl from "jexl";

export default class Grid
{
    private filterManager: FilterManager;
    private columnManager: ColumnManager;
    private router: Router;
    private eventDispatcher: EventDispatcher;
    private configuration: GridConfiguration;
    private view: View;
    private translator: Translator;
    private source: CancelTokenSource;
    private flashMessenger: FlashMessenger;

    constructor(
        filterManager: FilterManager,
        columnManager: ColumnManager,
        batchManager: BatchManager,
        router: Router,
        eventDispatcher: EventDispatcher,
        configuration: GridConfiguration,
        view: View,
        translator: Translator,
        flashMessenger: FlashMessenger
    ) {
        this.filterManager = filterManager;
        this.columnManager = columnManager;
        this.router = router;
        this.eventDispatcher = eventDispatcher;
        this.view = view;
        this.translator = translator;
        this.flashMessenger = flashMessenger;

        _.extend(configuration, new GridConfiguration());
        this.configuration = configuration;
        this.checkColumnConditions();
        this.initListener();
    }

    private initListener()
    {
        this.eventDispatcher.on('updated', (event: UpdatedEvent) => {
            this.view.loadValue('edit-view', (id) => {
                if(event.id == parseInt(id)) {
                    this.loadTable();
                }
            });
        });

        $(document).on('gridFilter', (event, data) => {
            this.configuration.showFilter = data;
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

    public open(row: RowData)
    {
        let parameters: any = {};
        if(this.configuration.updateRouteParameters) {
            parameters = this.configuration.updateRouteParameters;
        }
        parameters.id = row.id;

        let url = this.router.generate(this.configuration.updateRoute, parameters);
        this.view.open(url, 'edit-view')
    }

    public applyFilter()
    {
        this.configuration.page = 1;
        this.loadTable();
    }

    private getCurrentBatch(): Batch
    {
        for(let batch of this.configuration.batches) {
            if(batch.key == this.configuration.batch) {
                return batch;
            }
        }
        return null;
    }

    public executeBatch()
    {
        let batch = this.getCurrentBatch();

        if(batch == null) {
            this.view.alert(this.translator.trans('enhavo_app.batch.message.no_batch_select'));
            return;
        }

        if(this.getSelectedIds().length == 0) {
            this.view.alert(this.translator.trans('enhavo_app.batch.message.no_row_select'));
            return;
        }

        let uri = this.router.generate(this.configuration.batchRoute, {
            type: batch.key,
            ids: this.getSelectedIds()
        });

        this.view.confirm(new Confirm(
            batch.confirmMessage,
            () => {
                this.view.loading();
                axios.post(uri)
                    .then((response) => {
                        this.view.loaded();
                        this.configuration.page = 1;
                        this.loadTable();
                        this.flashMessenger.addMessage(new Message(
                            'success',
                            this.translator.trans('enhavo_app.batch.message.success')
                        ))
                    })
                    .catch((error) => {
                        this.view.loaded();
                        this.flashMessenger.addMessage(new Message(
                            'error',
                            this.translator.trans('enhavo_app.batch.message.error')
                        ))
                    })
            },
            () => {},
            this.translator.trans('enhavo_app.view.label.cancel'),
            this.translator.trans('enhavo_app.view.label.ok'),
        ))
    }

    private getSelectedIds()
    {
        let ids = [];
        for(let row of this.configuration.rows) {
            if(row.selected) {
                ids.push(row.id)
            }
        }
        return ids;
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

        let parameters: any = {};
        if(this.configuration.tableRouteParameters) {
            parameters = this.configuration.tableRouteParameters;
        }

        parameters.page = this.configuration.page;
        parameters.limit = this.configuration.pagination;

        let url = this.router.generate(this.configuration.tableRoute, parameters);

        if(this.source != null) {
            this.source.cancel();
        }
        this.source = axios.CancelToken.source();
        axios
            .post(url, {
                filters: this.filterManager.getFilterParameters(),
                sorting: this.columnManager.getSortingParameters()
            }, {
                cancelToken: this.source.token
            })
            // executed on success
            .then(response => {
                this.source = null;
                this.configuration.rows = this.createRowData(response.data.resources);
                this.configuration.count = parseInt(response.data.pages.count);
                this.configuration.page = parseInt(response.data.pages.page);
                this.configuration.loading = false;
            })
            // executed on error
            .catch(error => {

            })
    }

    private createRowData(objects: object[]): RowData[]
    {
        let data = [];
        for(let row of objects) {
            let rowData = new RowData();
            data.push(_.extend(row, rowData));
        }
        return data;
    }

    private checkColumnCondition(column: ColumnInterface): boolean {
        let context = {
            mobile: window.innerWidth <= 360,
            tablet: window.innerWidth > 360 && window.innerWidth <= 768,
            desktop: window.innerWidth > 768,
            width: window.innerWidth,
            this: column
        };
        if(column.condition) {
            return jexl.evalSync(column.condition, context);
        }
        return true;
    }

    private checkColumnConditions()
    {
        for(let column of this.configuration.columns) {
            column.display = this.checkColumnCondition(column);
        }
    }

    public resize()
    {
        this.checkColumnConditions();
    }
}