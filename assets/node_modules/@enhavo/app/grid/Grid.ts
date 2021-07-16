import FilterManager from "@enhavo/app/grid/filter/FilterManager";
import ColumnManager from "@enhavo/app/grid/column/ColumnManager";
import RowData from "@enhavo/app/grid/column/RowData";
import ColumnInterface from "@enhavo/app/grid/column/ColumnInterface";
import Router from "@enhavo/core/Router";
import GridConfiguration from "@enhavo/app/grid/GridConfiguration";
import axios, {CancelTokenSource} from 'axios';
import * as _ from "lodash";
import * as $ from "jquery";
import BatchManager from "@enhavo/app/grid/batch/BatchManager";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import View from "@enhavo/app/view/View";
import UpdatedEvent from "@enhavo/app/view-stack/event/UpdatedEvent";
import Translator from "@enhavo/core/Translator";
import FlashMessenger from "@enhavo/app/flash-message/FlashMessenger";
import * as jexl from "jexl";
import ViewInterface from "@enhavo/app/view-stack/ViewInterface";
import * as async from "async";
import Confirm from "@enhavo/app/view/Confirm";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

export default class Grid
{
    public configuration: GridConfiguration;
    private source: CancelTokenSource;

    private readonly filterManager: FilterManager;
    private readonly columnManager: ColumnManager;
    private readonly batchManager: BatchManager;
    private readonly router: Router;
    private readonly eventDispatcher: EventDispatcher;
    private readonly view: View;
    private readonly translator: Translator;
    private readonly flashMessenger: FlashMessenger;
    private readonly componentRegistry: ComponentRegistryInterface;

    constructor(
        filterManager: FilterManager,
        columnManager: ColumnManager,
        batchManager: BatchManager,
        router: Router,
        eventDispatcher: EventDispatcher,
        configuration: GridConfiguration,
        view: View,
        translator: Translator,
        flashMessenger: FlashMessenger,
        componentRegistry: ComponentRegistryInterface
    ) {
        this.filterManager = filterManager;
        this.columnManager = columnManager;
        this.batchManager = batchManager;
        this.router = router;
        this.eventDispatcher = eventDispatcher;
        this.view = view;
        this.translator = translator;
        this.flashMessenger = flashMessenger;
        this.componentRegistry = componentRegistry;

        _.extend(configuration, new GridConfiguration());
        this.configuration = configuration;
    }

    public init()
    {
        this.checkColumnConditions();
        this.initListener();

        this.batchManager.init();
        this.filterManager.init();
        this.columnManager.init();
        this.view.init();

        this.componentRegistry.registerStore('grid', this);
        this.componentRegistry.registerData(this.configuration);
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

        this.eventDispatcher.on('removed', (event: UpdatedEvent) => {
            this.view.loadValue('active-view', (id) => {
                if(event.id == parseInt(id)) {
                    this.clearActiveRow();
                }
            });
        });

        this.eventDispatcher.on('loaded', (event: UpdatedEvent) => {
            this.view.loadValue('edit-view', (id) => {
                if(event.id == parseInt(id)) {
                    this.checkActiveRow();
                }
            });
        });
    }

    public load()
    {
        this.loadTable();
    }

    public changePage(page: number)
    {
        this.configuration.page = page;
        this.trimPages();
        this.loadTable();
    }

    public trimPages(maxLength: number = 5)
    {
        this.configuration.pages.length = 0; // empty array but keep reference
        let numberOfPages = Math.ceil(this.configuration.count / this.configuration.pagination);
        for (let i = 1; i <= numberOfPages; i++) {
            this.configuration.pages.push(i);
        }

        if (this.configuration.pages.length <= maxLength) {
            return;
        }

        let leftTrim = Math.ceil((maxLength - 1) / 2);
        let rightTrim = Math.floor((maxLength - 1) / 2);

        if (this.configuration.pages.length - this.configuration.page <= rightTrim) {
            let newRightTrim = this.configuration.pages.length - this.configuration.page;
            leftTrim += rightTrim - newRightTrim;
            rightTrim = newRightTrim;
        } else if (this.configuration.page <= leftTrim) {
            let newLeftTrim = this.configuration.page - 1;
            rightTrim += leftTrim - newLeftTrim;
            leftTrim = newLeftTrim;
        }


        this.configuration.pages.splice(0,  this.configuration.pages.indexOf(this.configuration.page) - leftTrim);
        let rightTrimIndex = this.configuration.pages.indexOf(this.configuration.page) + rightTrim + 1;
        this.configuration.pages.splice(rightTrimIndex, this.configuration.pages.length - rightTrimIndex + 1);
    }

    public changePagination(number: number)
    {
        this.configuration.page = 1;
        this.configuration.pagination = number;
        this.loadTable();
    }

    public changeSelect(row: RowData, value: boolean)
    {
        this.configuration.selectAll = !value ? false : this.configuration.selectAll;

        row.selected = value;

        let index = this.configuration.selectedIds.indexOf(row.id);

        // add id if necessary
        if (value && index === -1) {
            this.configuration.selectedIds.push(row.id);

        // remove id if necessary
        } else if (false == value && index !== -1) {
            this.configuration.selectedIds.splice(index, 1);
        }
    }

    public changeSelectAll(value: boolean)
    {
        this.configuration.selectAll = value;
        this.resetSelectedIds();

        if (this.hasPages()) {
            if(value) {
                this.markAllEntries();
            } else {
                this.markAllRowsWith(false);
            }
        } else {
            if(value) {
                this.markVisibleEntries();
                this.markAllRowsWith(true);
            } else {
                this.markAllRowsWith(false);
            }
        }
    }

    private resetSelectedIds()
    {
        this.configuration.selectedIds.splice(0, this.configuration.selectedIds.length);
    }

    private hasSelectedIds()
    {
        return this.configuration.selectedIds.length > 0;
    }

    private hasPages()
    {
        return this.configuration.pagination < this.configuration.count;
    }

    private markAllRowsWith(value: boolean)
    {
        for(let row of this.configuration.rows) {
            row.selected = value;
        }
    }

    private markVisibleEntries()
    {
        for(let row of this.configuration.rows) {
            this.configuration.selectedIds.push(row.id);
        }
    }

    private markAllEntries()
    {
        this.configuration.loading = true;

        let parameters: any = {};
        if(this.configuration.tableRouteParameters) {
            parameters = this.configuration.tableRouteParameters;
        }

        parameters.hydrate = 'id';
        parameters.paginate = 0;
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
                for (let index in response.data.resources) {
                    this.configuration.selectedIds.push(response.data.resources[index].id);
                }

                this.checkSelectedRows();

                this.source = null;
                this.configuration.loading = false;

            })
            // executed on error
            .catch(error => {

            })
    }

    public open(row: RowData)
    {
        let parameters: any = {};
        if(this.configuration.openRouteParameters) {
            parameters = this.configuration.openRouteParameters;
        }
        parameters.id = row.id;

        this.parseParameters(parameters, {
            row: row,
            data: row.data,
            id: row.id,
        });

        this.activateRow(row).then(() => {
            let url = this.router.generate(this.configuration.openRoute, parameters);
            this.view.open(url, 'edit-view').then((view: ViewInterface) => {
                this.view.storeValue('active-view', view.id);
            });
        });
    }

    private parseParameters(parameters: object, context: any)
    {
        for (const [key, value] of Object.entries(parameters)) {
            if (typeof value === 'string' && value.match(/^jexl:(.+)/)) {
                parameters[key] = jexl.evalSync(value.substr(5), context);
            }
        }
    }

    public applyFilter()
    {
        this.configuration.page = 1;
        this.configuration.selectAll = false;
        if(this.hasSelectedIds()) {
            this.view.confirm(new Confirm(
                this.translator.trans('enhavo_app.view.message.unmark_after_filter'),
                () => {
                    this.resetSelectedIds();
                    this.loadTable();
                },
                () => {},
                this.translator.trans('enhavo_app.view.label.cancel'),
                this.translator.trans('enhavo_app.view.label.ok'),
            ))
        } else {
            this.resetSelectedIds();
            this.loadTable();
        }
    }

    public resetFilter()
    {
        this.filterManager.reset();
    }

    public executeBatch()
    {
        let ids = this.configuration.selectedIds;
        this.batchManager.execute(ids).then((reload: boolean) => {
            if(reload) {
                this.configuration.page = 1;
                this.loadTable();
            }
        });
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
        this.configuration.loading = true;

        let parameters: any = {};
        if(this.configuration.tableRouteParameters) {
            parameters = this.configuration.tableRouteParameters;
        }

        if(this.configuration.paginate) {
            parameters.page = this.configuration.page;
            parameters.limit = this.configuration.pagination;
        }

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
                this.configuration.loading = false;

                if(this.configuration.paginate) {
                    this.configuration.count = parseInt(response.data.pages.count);
                    this.configuration.page = parseInt(response.data.pages.page);
                }

                this.trimPages();
                this.checkSelectedRows();
                this.checkActiveRow();
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

    private activateRow(row: RowData)
    {
        return new Promise((resolve, reject) => {
            for(let currentRow of this.configuration.rows) {
                currentRow.active = currentRow.id === row.id;
            }

            async.parallel([(callback: (err: any) => void) => {
                this.view.storeValue('active-view', null).then(() => {
                    callback(null);
                }).catch(() => {
                    callback(true);
                });
            },(callback: (err: any) => void) => {
                this.view.storeValue('active-row', row.id).then(() => {
                    callback(null);
                }).catch(() => {
                    callback(true);
                });
            }], (err) => {
                if(err) {
                    reject();
                } else {
                    resolve();
                }
            });
        });
    }

    private checkSelectedRows()
    {
        for(let currentRow of this.configuration.rows) {
            if (this.configuration.selectedIds.indexOf(currentRow.id) !== -1) {
                currentRow.selected = true;
            }
        }
    }

    private checkActiveRow()
    {
        this.view.loadValue('active-row', (id) => {
            if(id) {
                for(let currentRow of this.configuration.rows) {
                    currentRow.active = currentRow.id === parseInt(id);
                }
            }
        });
    }

    public clearActiveRow()
    {
        this.view.storeValue('active-view', null);
        this.view.storeValue('active-row', null);
        for(let currentRow of this.configuration.rows) {
            currentRow.active = false;
        }
    }

    public hasPagination()
    {
        return this.configuration.pagination;
    }
}
