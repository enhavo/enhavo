import Application from "@enhavo/app/Application";
import AppInterface from "@enhavo/app/AppInterface";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";
import DashboardApp from "@enhavo/dashboard/DashboardApp";
import WidgetManager from "@enhavo/dashboard/Widget/WidgetManager";
import WidgetRegistry from "@enhavo/dashboard/Widget/WidgetRegistry";

export class DashboardApplication extends Application implements ActionAwareApplication
{
    protected dashboardManager: WidgetManager;
    protected widgetRegistry: WidgetRegistry;

    public getApp(): AppInterface
    {
        if(this.app == null) {
            this.app = new DashboardApp(this.getDataLoader(), this.getEventDispatcher(), this.getView(), this.getWidgetManager());
        }
        return this.app;
    }

    public getWidgetManager(): WidgetManager
    {
        if(this.actionManager == null) {
            this.dashboardManager = new WidgetManager(this.getDataLoader().load().widgets, this.getWidgetRegistry());
        }
        return this.dashboardManager;
    }

    public getWidgetRegistry(): WidgetRegistry
    {
        if(this.widgetRegistry == null) {
            this.widgetRegistry = new WidgetRegistry();
        }
        return this.widgetRegistry;
    }
}

let application = new DashboardApplication();
export default application;