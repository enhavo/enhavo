import FilterManager from "@enhavo/app/Grid/Filter/FilterManager";
import ColumnManager from "@enhavo/app/Grid/Column/ColumnManager";
import RowData from "@enhavo/app/Grid/Column/RowData";
import ColumnInterface from "@enhavo/app/Grid/Column/ColumnInterface";
import Router from "@enhavo/core/Router";
import GridConfiguration from "@enhavo/app/Grid/GridConfiguration";
import axios, {CancelTokenSource} from 'axios';
import * as _ from "lodash";
import BatchManager from "@enhavo/app/Grid/Batch/BatchManager";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import CreateEvent from "@enhavo/app/ViewStack/Event/CreateEvent";
import View from "@enhavo/app/View/View";
import ViewInterface from "@enhavo/app/ViewStack/ViewInterface";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";
import RemovedEvent from "@enhavo/app/ViewStack/Event/RemovedEvent";
import UpdatedEvent from "@enhavo/app/ViewStack/Event/UpdatedEvent";
import Translator from "@enhavo/core/Translator";
import Confirm from "@enhavo/app/View/Confirm";
import Batch from "@enhavo/app/Grid/Batch/Batch";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";
import Message from "@enhavo/app/FlashMessage/Message";

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
            label: this.translator.trans('enhavo_app.edit'),
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
            }
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
        let url = this.router.generate(this.configuration.tableRoute, {
            page: this.configuration.page,
            limit: this.configuration.pagination
        });


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
            data.push(_.extend(rowData, row));
        }
        return data;
    }
}