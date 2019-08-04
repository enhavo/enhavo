import AbstractApplication from "@enhavo/app/AbstractApplication";
import AppInterface from "@enhavo/app/AppInterface";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";
import DashboardApp from "@enhavo/dashboard/Dashboard/DashboardApp";

export class DashboardApplication extends AbstractApplication implements ActionAwareApplication
{
    public getApp(): AppInterface
    {
        if(this.app == null) {
            this.app = new DashboardApp(this.getDataLoader(), this.getEventDispatcher(), this.getView(), this.getActionManager());
        }
        return this.app;
    }
}

let application = new DashboardApplication();
export default application;