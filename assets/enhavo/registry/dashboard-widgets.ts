import RegistryPackage from "@enhavo/core/RegistryPackage";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import WidgetRegistryPackage from "@enhavo/dashboard/Widget/WidgetRegistryPackage";

export default class DashboardWidgetsRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface) {
        super();
        this.registerPackage(new WidgetRegistryPackage(application));
    }
}