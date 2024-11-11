import {DashboardWidgetInterface} from "@enhavo/dashboard/dashboard/DashboardWidgetInterface";
import {ModelFactory} from "@enhavo/app/factory/ModelFactory";

export class DashboardWidgetFactory extends ModelFactory
{
    createWithData(name: string, data: object): DashboardWidgetInterface
    {
        return <DashboardWidgetInterface>super.createWithData(name, data)
    }

    createNew(name: string): DashboardWidgetInterface
    {
        return <DashboardWidgetInterface>super.createNew(name);
    }
}