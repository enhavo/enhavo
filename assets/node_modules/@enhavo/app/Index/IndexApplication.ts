import IndexApp from "@enhavo/app/Index/IndexApp";
import ActionManager from "@enhavo/app/Action/ActionManager";
import AbstractApplication from "@enhavo/app/AbstractApplication";
import AppInterface from "@enhavo/app/AppInterface";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";
import Grid from "@enhavo/app/Grid/Grid";
import FilterManager from "@enhavo/app/Grid/Filter/FilterManager";
import FilterRegistry from "@enhavo/app/Grid/Filter/FilterRegistry";
import ColumnManager from "@enhavo/app/Grid/Column/ColumnManager";
import ColumnRegistry from "@enhavo/app/Grid/Column/ColumnRegistry";
import BatchManager from "@enhavo/app/Grid/Batch/BatchManager";
import Editable from "@enhavo/app/Action/Editable";

export class IndexApplication extends AbstractApplication implements ActionAwareApplication
{
    protected grid: Grid;
    protected filterManager: FilterManager;
    protected filterRegistry: FilterRegistry;
    protected columnManager: ColumnManager;
    protected columnRegistry: ColumnRegistry;
    protected batchManager: BatchManager;

    constructor()
    {
        super();
    }

    public getApp(): AppInterface
    {
        if(this.app == null) {
            this.app = new IndexApp(this.getDataLoader(), this.getEventDispatcher(), this.getView(), this.getActionManager());
        }
        return this.app;
    }

    public getGrid(): Grid
    {
        if(this.grid == null) {
            this.grid = new Grid(
                this.getFilterManager(),
                this.getColumnManager(),
                this.getBatchManager(),
                this.getRouter(),
                this.getEventDispatcher(),
                this.getDataLoader().load().grid,
                this.getView(),
                this.getTranslator(),
                this.getFlashMessenger(),
            );
        }
        return this.grid;
    }

    public getFilterManager(): FilterManager
    {
        if(this.filterManager == null) {
            this.filterManager = new FilterManager(this.getDataLoader().load().grid.filters, this.getFilterRegistry());
        }
        return this.filterManager;
    }

    public getFilterRegistry(): FilterRegistry
    {
        if(this.filterRegistry == null) {
            this.filterRegistry = new FilterRegistry();
            this.filterRegistry.load(this);
        }
        return this.filterRegistry;
    }

    public getColumnManager(): ColumnManager
    {
        if(this.columnManager == null) {
            this.columnManager = new ColumnManager(this.getDataLoader().load().grid.columns, this.getColumnRegistry());
        }
        return this.columnManager;
    }

    public getColumnRegistry(): ColumnRegistry
    {
        if(this.columnRegistry == null) {
            this.columnRegistry = new ColumnRegistry();
            this.columnRegistry.load(this);
        }
        return this.columnRegistry;
    }

    public getBatchManager(): BatchManager
    {
        if(this.batchManager == null) {
            this.batchManager = new BatchManager(this.getDataLoader().load().grid.batches);
        }
        return this.batchManager;
    }

    public getEditable(): Editable
    {
        return this.getGrid();
    }
}

let application = new IndexApplication();
export default application;