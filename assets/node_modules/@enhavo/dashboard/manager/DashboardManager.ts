import {DashboardWidgetManager} from "@enhavo/dashboard/dashboard/DashboardWidgetManager";
import {DashboardWidgetInterface} from "@enhavo/dashboard/dashboard/DashboardWidgetInterface";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {Router} from "@enhavo/app/routing/Router";

export class DashboardManager
{
    public widgets: DashboardWidgetInterface[]

    constructor(
        private frameManager: FrameManager,
        private dashboardWidgetManager: DashboardWidgetManager,
        private router: Router,
    ) {
    }

    async load(): Promise<void>
    {
        const url = this.router.generate('enhavo_dashboard_admin_api_index');

        fetch(url).then((response) => {
            response.json().then((data: any) => {
                this.widgets = this.dashboardWidgetManager.createDashboardWidgets(data.widgets);
                this.frameManager.setLabel('Dashboard');
                this.frameManager.loaded();
            });
        });
    }
}
