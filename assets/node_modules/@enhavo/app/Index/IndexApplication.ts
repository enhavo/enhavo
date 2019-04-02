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

export class IndexApplication extends AbstractApplication implements ActionAwareApplication
{
    protected actionManager: ActionManager;
    protected actionRegistry: ActionRegistry;
    protected grid: Grid;
    protected filterManager: FilterManager;
    protected filterRegistry: FilterRegistry;
    protected columnManager: ColumnManager;
    protected columnRegistry: ColumnRegistry;

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

    public getActionManager(): ActionManager
    {
        if(this.actionManager == null) {
            this.actionManager = new ActionManager(this.getDataLoader().load().actions, this.getActionRegistry());
        }
        return this.actionManager;
    }

    public getActionRegistry(): ActionRegistry
    {
        if(this.actionRegistry == null) {
            this.actionRegistry = new ActionRegistry();
            this.actionRegistry.load(this);
        }
        return this.actionRegistry;
    }

    public getGrid(): Grid
    {
        if(this.grid == null) {
            this.grid = new Grid(this.getFilterManager(), this.getColumnManager(), this.getRouter(), this.getDataLoader().load().grid);
        }
        return this.grid;
    }

    public getFilterManager(): FilterManager
    {
        if(this.filterManager == null) {
            this.filterManager = new FilterManager(this.getDataLoader().load().filters, this.getFilterRegistry());
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
}

let application = new IndexApplication();
export default application;