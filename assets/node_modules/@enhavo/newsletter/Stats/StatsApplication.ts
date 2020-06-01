import StatsApp from "@enhavo/newsletter/Stats/StatsApp";
import Application from "@enhavo/app/Application";
import AppInterface from "@enhavo/app/AppInterface";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";

export class StatsApplication extends Application implements ActionAwareApplication
{
    public getApp(): AppInterface
    {
        if (this.app == null) {
            this.app = new StatsApp(this.getDataLoader(), this.getEventDispatcher(), this.getView(), this.getActionManager());
        }
        return this.app;
    }
}

let application = new StatsApplication();
export default application;
