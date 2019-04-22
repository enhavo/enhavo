import App from "@enhavo/app/List/ListApp";
import ActionManager from "@enhavo/app/Action/ActionManager";
import AbstractApplication from "@enhavo/app/AbstractApplication";
import AppInterface from "@enhavo/app/AppInterface";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";
import ColumnManager from "@enhavo/app/Grid/Column/ColumnManager";
import ColumnRegistry from "@enhavo/app/Grid/Column/ColumnRegistry";
import List from "@enhavo/app/List/List";
import Editable from "@enhavo/app/Action/Editable";

export class ListApplication extends AbstractApplication implements ActionAwareApplication
{
    protected actionManager: ActionManager;
    protected actionRegistry: ActionRegistry;
    protected columnManager: ColumnManager;
    protected columnRegistry: ColumnRegistry;
    protected list: List;

    constructor()
    {
        super();
    }

    public getApp(): AppInterface
    {
        if(this.app == null) {
            this.app = new App(this.getDataLoader(), this.getEventDispatcher(), this.getView(), this.getActionManager());
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

    public getList(): List
    {
        if(this.list == null) {
            this.list = new List(
                this.getDataLoader().load().list,
                this.getEventDispatcher(),
                this.getView(),
                this.getColumnManager(),
                this.getRouter(),
                this.getTranslator(),
            )
        }
        return this.list;
    }

    public getColumnManager(): ColumnManager
    {
        if(this.columnManager == null) {
            this.columnManager = new ColumnManager(this.getDataLoader().load().list.columns, this.getColumnRegistry());
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

    public getEditable(): Editable
    {
        return this.getList();
    }
}

let application = new ListApplication();
export default application;