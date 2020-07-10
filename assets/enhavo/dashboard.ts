import Application from "@enhavo/dashboard/DashboardApplication";
import DashboardWidgetsRegistryPackage from "./registry/dashboard-widgets";

Application.getWidgetRegistry().registerPackage(new DashboardWidgetsRegistryPackage(Application));
Application.getVueLoader().load(() => import("@enhavo/dashboard/Widget/Components/ApplicationComponent.vue"));