import {ActionManager} from "@enhavo/app/action/ActionManager";
import {ActionInterface} from "@enhavo/app/action/ActionInterface";
import {FilterManager} from "@enhavo/app/filter/FilterManager";
import {FilterInterface} from "@enhavo/app/filter/FilterInterface";
import {ColumnInterface} from "@enhavo/app/column/ColumnInterface";
import {CollectionInterface} from "@enhavo/app/collection/CollectionInterface";
import {ColumnManager} from "@enhavo/app/column/ColumnManager";
import {CollectionFactory} from "@enhavo/app/collection/CollectionFactory";
import {BatchManager} from "@enhavo/app/batch/BatchManager";
import {BatchInterface} from "@enhavo/app/batch/BatchInterface";
import {RouteContainer} from "@enhavo/app/routing/RouteContainer";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {UiManager} from "@enhavo/app/ui/UiManager";

export class ResourceIndexManager
{
    public actions: ActionInterface[];
    public actionsSecondary: ActionInterface[];
    public filters: FilterInterface[];
    public columns: ColumnInterface[];
    public batches: BatchInterface[];
    public collection: CollectionInterface;
    public routes: RouteContainer;

    private loadedPromiseResolveCalls: Array<() => void> = [];
    private loaded: boolean = false;

    constructor(
        private frameManager: FrameManager,
        private actionManager: ActionManager,
        private filterManager: FilterManager,
        private columnManager: ColumnManager,
        private batchManager: BatchManager,
        private collectionFactory: CollectionFactory,
        private uiManager: UiManager,
    ) {
    }

    async load(url: string)
    {
        const response = await fetch(url);

        if (!response.ok) {
            this.frameManager.loaded();
            this.uiManager.alert({ message: 'Error occured' }).then(() => {
                this.frameManager.close(true);
            });
            return;
        }

        const data = await response.json();

        this.actions = this.actionManager.createActions(data.actions);
        this.actionsSecondary = this.actionManager.createActions(data.actionsSecondary);
        this.filters = this.filterManager.createFilters(data.filters);
        this.columns = this.columnManager.createColumns(data.columns);
        this.batches = this.batchManager.createBatches(data.batches);
        this.routes = new RouteContainer(data.routes);
        this.collection = this.collectionFactory.create(data.collection.model, data.collection, this.filters, this.columns, this.batches, this.routes);
        this.collection.init();

        this.loaded = true
        for (let promise of this.loadedPromiseResolveCalls) {
            promise();
        }

        this.frameManager.loaded();
    }

    onLoaded(): Promise<void>
    {
        return new Promise(resolve => {
            if (this.loaded) {
                resolve();
            } else {
                this.loadedPromiseResolveCalls.push(resolve);
            }
        })
    }

    applyFilters()
    {
        this.collection.load();
    }

    resetFilters()
    {
        for (let filter of this.filters) {
            filter.reset();
        }
    }
}
