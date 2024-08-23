import {Router} from "@enhavo/app/routing/Router";
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
import {RouteContainer} from "../routing/RouteContainer";
import {FrameManager} from "../frame/FrameManager";

export class ResourceIndexManager
{
    public actions: ActionInterface[];
    public actionsSecondary: ActionInterface[];
    public filters: FilterInterface[];
    public columns: ColumnInterface[];
    public batches: BatchInterface[];
    public collection: CollectionInterface;
    public routes: RouteContainer;

    constructor(
        private router: Router,
        private frameManager: FrameManager,
        private actionManager: ActionManager,
        private filterManager: FilterManager,
        private columnManager: ColumnManager,
        private batchManager: BatchManager,
        private collectionFactory: CollectionFactory,
    ) {
    }

    async loadIndex(route: string)
    {
        let url = this.router.generate(route);

        const response = await fetch(url);
        const data = await response.json();

        this.actions = this.actionManager.createActions(data.actions);
        this.actionsSecondary = this.actionManager.createActions(data.actionsSecondary);
        this.filters = this.filterManager.createFilters(data.filters);
        this.columns = this.columnManager.createColumns(data.columns);
        this.batches = this.batchManager.createBatches(data.batches);
        this.routes = new RouteContainer(data.routes);
        this.collection = this.collectionFactory.create(data.collection.model, data.collection, this.filters, this.columns, this.batches, this.routes);

        this.frameManager.loaded();
    }
}
