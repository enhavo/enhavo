import {CollectionResourceItem} from "@enhavo/app/collection/CollectionResourceItem";
import {Router} from "@enhavo/app/routing/Router";
import {FilterManager} from "@enhavo/app/filter/FilterManager";
import {ColumnManager} from "@enhavo/app/column/ColumnManager";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {RouteContainer} from "@enhavo/app/routing/RouteContainer";
import {ColumnInterface} from "@enhavo/app/column/ColumnInterface";
import {FilterInterface} from "@enhavo/app/filter/FilterInterface";
import {BatchInterface} from "@enhavo/app/batch/BatchInterface";
import {Event} from "@enhavo/app/frame/FrameEventDispatcher";
import {Translator} from "@enhavo/app/translation/Translator";
import {FlashMessage, FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import jexl from "jexl";
import axios from "axios";

export class ListCollection
{
    private abortController: AbortController = null;

    routes: RouteContainer;
    loading: boolean = false;
    selectedIds: number[] = [];
    expandedIds: number[] = [];
    columns: ColumnInterface[];
    filters: FilterInterface[];
    batches: BatchInterface[];
    items: CollectionResourceItem[] = [];
    csrfToken: string;

    sortable: boolean = false;
    dragging: boolean = false;
    treeable: boolean = false;
    filtered: boolean = false;

    constructor(
        private router: Router,
        private filterManager: FilterManager,
        private columnManager: ColumnManager,
        private frameManager: FrameManager,
        private flashMessenger: FlashMessenger,
        private translator: Translator,
    ) {
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

    getIds(): Array<number>
    {
        return this.selectedIds;
    }

    async load(): Promise<boolean>
    {
        this.loading = true;

        let parameters = this.routes.getParameters('list');

        let data = await this.fetch(parameters);

        this.items = this.createRowData(data.items);
        this.filtered = data.meta.filtered;
        this.loading = false;

        this.checkSelectedItems();
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

        return await response.json();
    }

    private createRowData(objects: object[]): CollectionResourceItem[]
    {
        let items = [];
        for (let row of objects) {
            let item = new CollectionResourceItem();
            Object.assign(item, row);
            items.push(item);
        }
        return items;
    }

    private checkSelectedItems()
    {
        for (let currentRow of this.items) {
            if (this.selectedIds.indexOf(currentRow.id) !== -1) {
                currentRow.selected = true;
            }
        }
    }

    private async checkActiveRow()
    {
        const frames = await this.frameManager.getFrames();

        for (let row of this.items) {
            row.active = false;
        }

        for (let frame of frames) {
            for (let row of this.items) {
                if (row.url === frame.url) {
                    row.active = true;
                }
            }
        }
    }

    public resize()
    {
        this.checkColumnConditions();
    }

    private checkColumnConditions()
    {
        for(let column of this.columns) {
            column.display = this.checkColumnCondition(column);
        }
    }

    private checkColumnCondition(column: ColumnInterface): boolean
    {
        let context = {
            mobile: window.innerWidth <= 360,
            tablet: window.innerWidth > 360 && window.innerWidth <= 768,
            desktop: window.innerWidth > 768,
            width: window.innerWidth,
            this: column
        };
        if (column.condition) {
            return jexl.evalSync(column.condition, context);
        }
        return true;
    }

    async open(row: CollectionResourceItem) {
        if (row.url) {
            await this.frameManager.openFrame({
                url: row.url,
                key: 'edit',
            });
        }
    }

    save(parent: CollectionResourceItem)
    {
        let items: CollectionResourceItem[] = null;
        if (parent === null) {
            items = this.items
        } else {
            items = parent.children;
        }

        let ids = [];
        for (let item of items) {
            ids.push(item.id);
        }

        let data = {
            action: 'sort',
            parent: parent ? parent.id : null,
            items: ids,
            csrfToken: this.csrfToken,
        };

        const route = this.routes.getRoute('list');

        let url = this.router.generate(route);

        axios
            .post(url, data)
            // executed on success
            .then(response => {
                this.flashMessenger.add(
                    this.translator.trans('enhavo_app.list.message.save', {}, 'javascript')
                )
            })
            // executed on error
            .catch(error => {
                this.flashMessenger.add(
                    this.translator.trans('enhavo_app.list.message.error', {}, 'javascript'),
                    FlashMessage.ERROR,
                );
            })
    }

    public changeSelect(item: CollectionResourceItem, value: boolean)
    {
        item.selected = value;

        let index = this.selectedIds.indexOf(item.id);

        if (value && index === -1) {
            this.selectedIds.push(item.id);
        } else if (false == value && index !== -1) {
            this.selectedIds.splice(index, 1);
        }
    }
}
