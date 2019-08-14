import App from "@enhavo/app/List/ListApp";
import AbstractApplication from "@enhavo/app/AbstractApplication";
import AppInterface from "@enhavo/app/AppInterface";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";
import ColumnManager from "@enhavo/app/Grid/Column/ColumnManager";
import ColumnRegistry from "@enhavo/app/Grid/Column/ColumnRegistry";
import List from "@enhavo/app/List/List";

export class ListApplication extends AbstractApplication implements ActionAwareApplication
{
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
                this.getFlashMessenger()
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
        }
        return this.columnRegistry;
    }
}

let application = new ListApplication();
export default application;