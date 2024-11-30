import {CollectionInterface} from "../CollectionInterface";
import {ColumnInterface} from "../../column/ColumnInterface";
import {AbstractColumn} from "../../column/model/AbstractColumn";
import {FilterInterface} from "../../filter/FilterInterface";
import {RouteContainer} from "../../routing/RouteContainer";
import {Router} from "../../routing/Router";
import {FilterManager} from "../../filter/FilterManager";
import {ColumnManager} from "../../column/ColumnManager";
import {CollectionResourceItem} from "../CollectionResourceItem";
import {BatchInterface} from "../../batch/BatchInterface";
import {FrameManager} from "../../frame/FrameManager";
import {Event} from "../../frame/FrameEventDispatcher";
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import {Translator} from "@enhavo/app/translation/Translator";


export class TableCollection implements CollectionInterface
{
    private abortController: AbortController = null;

    component: string;
    page: number;
    pages: number[] = [];
    paginationSteps: number[];
    paginationStep: number;
    paginated: boolean;
    selectedAll: boolean;
    selectedIds: number[] = [];
    columns: ColumnInterface[];
    filters: FilterInterface[];
    batches: BatchInterface[];
    routes: RouteContainer;
    loading: boolean = false;
    rows: CollectionResourceItem[] = [];
    count: number;

    constructor(
        protected router: Router,
        protected filterManager: FilterManager,
        protected columnManager: ColumnManager,
        protected frameManager: FrameManager,
        protected flashMessenger: FlashMessenger,
        protected translator: Translator,
    ) {
    }

    getIds(): Array<number>
    {
        return this.selectedIds;
    }

    init(): void
    {
        this.frameManager.on('frame_added', (event: Event) => {
            this.checkActiveRow();
        });

        this.frameManager.on('frame_removed', (event: Event) => {
            this.checkActiveRow();
        });

        this.frameManager.on('input_changed', (event: Event) => {
            this.load();
        });
    }

    async load(): Promise<boolean>
    {
        this.loading = true;

        let parameters = this.routes.getParameters('list');

        if (this.paginated) {
            parameters['page'] = this.page;
            parameters['limit'] = this.paginationStep;
        }

        let data = await this.fetch(parameters);
        if (data === false) {
            this.loading = false;
            return;
        }

        this.rows = this.createRowData(data.items);
        this.loading = false;

        if (this.paginated) {
            this.count = parseInt(data.meta.count);
            this.page = parseInt(data.meta.page);
        }

        this.trimPages();
        this.checkSelectedRows();
        this.checkActiveRow();

        return true;
    }

    private async fetch(parameters: object): Promise<any>
    {
        this.loading = true;

        const route = this.routes.getRoute('list');
        if (route === null) {
            throw 'Table collection need a list route';
        }

        const body = {
            filters: this.filterManager.getFilterParameters(this.filters),
            sorting: this.columnManager.getSortingParameters(this.columns),
        }

        if (this.abortController !== null) {
            this.abortController.abort();
        }

        this.abortController = new AbortController();
        const response = await fetch(this.router.generate(route, parameters), {
            method: 'POST',
            body: JSON.stringify(body),
            signal: this.abortController.signal,
        });
        this.abortController = null;

        if (!response.ok) {
            this.flashMessenger.error(this.translator.trans('enhavo_app.error', {}, 'javascript'));
            return false;
        }

        return await response.json();
    }

    private createRowData(objects: object[]): CollectionResourceItem[]
    {
        let rows = [];
        for (let row of objects) {
            let item = new CollectionResourceItem();
            Object.assign(item, row);
            rows.push(item);
        }
        return rows;
    }

    private checkSelectedRows()
    {
        for (let currentRow of this.rows) {
            if (this.selectedIds.indexOf(currentRow.id) !== -1) {
                currentRow.selected = true;
            }
        }
    }

    private async checkActiveRow()
    {
        const frames = await this.frameManager.getFrames();

        for (let row of this.rows) {
            row.active = false;
        }

        for (let frame of frames) {
            for (let row of this.rows) {
                if (row.url === frame.url) {
                    row.active = true;
                }
            }
        }
    }

    private trimPages(maxLength: number = 5)
    {
        this.pages = [];
        let numberOfPages = Math.ceil(this.count / this.paginationStep);
        for (let i = 1; i <= numberOfPages; i++) {
            this.pages.push(i);
        }

        if (this.pages.length <= maxLength) {
            return;
        }

        let leftTrim = Math.ceil((maxLength - 1) / 2);
        let rightTrim = Math.floor((maxLength - 1) / 2);

        if (this.pages.length - this.page <= rightTrim) {
            let newRightTrim = this.pages.length - this.page;
            leftTrim += rightTrim - newRightTrim;
            rightTrim = newRightTrim;
        } else if (this.page <= leftTrim) {
            let newLeftTrim = this.page - 1;
            rightTrim += leftTrim - newLeftTrim;
            leftTrim = newLeftTrim;
        }


        this.pages.splice(0,  this.pages.indexOf(this.page) - leftTrim);
        let rightTrimIndex = this.pages.indexOf(this.page) + rightTrim + 1;
        this.pages.splice(rightTrimIndex, this.pages.length - rightTrimIndex + 1);
    }

    public changeSelect(row: CollectionResourceItem, value: boolean)
    {
        this.selectedAll = !value ? false : this.selectedAll;

        row.selected = value;

        let index = this.selectedIds.indexOf(row.id);

        // add id if necessary
        if (value && index === -1) {
            this.selectedIds.push(row.id);

            // remove id if necessary
        } else if (false == value && index !== -1) {
            this.selectedIds.splice(index, 1);
        }
    }

    public changeSelectAll(value: boolean)
    {
        this.selectedAll = value;
        this.resetSelectedIds();

        if (this.hasPages()) {
            if (value) {
                this.markAllEntries();
            } else {
                this.markAllRowsWith(false);
            }
        } else {
            if (value) {
                this.markVisibleEntries();
                this.markAllRowsWith(true);
            } else {
                this.markAllRowsWith(false);
            }
        }
    }

    private hasPages()
    {
        return this.pages.length > 1;
    }

    private resetSelectedIds()
    {
        this.selectedIds.splice(0, this.selectedIds.length);
    }

    private markAllRowsWith(value: boolean)
    {
        for (let row of this.rows) {
            row.selected = value;
        }
    }

    private markVisibleEntries()
    {
        for (let row of this.rows) {
            this.selectedIds.push(row.id);
        }
    }

    private async markAllEntries(): Promise<void>
    {
        this.loading = true;

        let parameters = {
            hydrate: 'id',
            paginated: 0
        };

        let data = await this.fetch(parameters);
        if (data === false) {
            this.loading = false;
            return;
        }

        for (let item of data.items) {
            this.selectedIds.push(item.id);
        }

        this.loading = false;
        this.checkSelectedRows();
    }

    async open(row: CollectionResourceItem) {
        if (row.url) {
            await this.frameManager.openFrame({
                url: row.url,
                key: 'edit',
            });
        }
    }
    changePage(page: number)
    {
        this.page = page;
        this.load();
    }

    changeSortDirection(column: ColumnInterface)
    {
        if (column.sortable) {
            let sortingState = column.sortingDirection;

            for (let item of this.columns) {
                item.sortingDirection = null;
            }

            if (sortingState === AbstractColumn.SORTING_DIRECTION_ASC) {
                column.sortingDirection = AbstractColumn.SORTING_DIRECTION_DESC;
            } else if (sortingState === AbstractColumn.SORTING_DIRECTION_DESC) {
                column.sortingDirection = null;
            } else if (sortingState === null) {
                column.sortingDirection = AbstractColumn.SORTING_DIRECTION_ASC;
            }
            this.load();
        }
    }
}
