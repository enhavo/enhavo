import {DashboardWidgetInterface} from "@enhavo/dashboard/dashboard/DashboardWidgetInterface";
export class AbstractDashboardWidget implements DashboardWidgetInterface
{
    component: string;
    model: string;
    key: string;
    position: number;
    row: number;
    width: number;
}
