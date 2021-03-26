import View from "@enhavo/app/view/View";
import WidgetManager from "@enhavo/dashboard/widget/WidgetManager";

export default class DashboardApp
{
    private widgetManager: WidgetManager;
    private view: View;

    constructor(view: View, widgetManager: WidgetManager)
    {
        this.widgetManager = widgetManager;
        this.view = view;
    }

    init() {
        this.widgetManager.init();
        this.view.init();

        this.view.addDefaultCloseListener();
        this.view.ready();
    }
}
