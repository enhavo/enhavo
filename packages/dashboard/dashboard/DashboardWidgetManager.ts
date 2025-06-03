import {DashboardWidgetFactory} from "@enhavo/dashboard/dashboard/DashboardWidgetFactory";
import {DashboardWidgetInterface} from "@enhavo/dashboard/dashboard/DashboardWidgetInterface";

export class DashboardWidgetManager
{
    constructor(
        private factory: DashboardWidgetFactory
    )
    {
    }

    createDashboardWidgets(columns: object): DashboardWidgetInterface[]
    {
        let data = [];
        for (let i in columns) {
            let column = this.factory.createWithData(columns[i].model, columns[i])
            data.push(column);
        }
        return data;
    }
}
