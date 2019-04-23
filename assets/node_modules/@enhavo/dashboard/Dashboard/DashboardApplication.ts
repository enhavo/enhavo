import ActionManager from "@enhavo/app/Action/ActionManager";
import AbstractApplication from "@enhavo/app/AbstractApplication";
import AppInterface from "@enhavo/app/AppInterface";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";
import DashboardApp from "@enhavo/dashboard/Dashboard/DashboardApp";

export class DashboardApplication extends AbstractApplication implements ActionAwareApplication
{
    protected actionManager: ActionManager;
    protected actionRegistry: ActionRegistry;

    public getApp(): AppInterface
    {
        if(this.app == null) {
            this.app = new DashboardApp(this.getDataLoader(), this.getEventDispatcher(), this.getView(), this.getActionManager());
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
            this.actionRegistry = new ActionRegistry;
            this.actionRegistry.load(this);
        }
        return this.actionRegistry;
    }
}

let application = new DashboardApplication();
export default application;