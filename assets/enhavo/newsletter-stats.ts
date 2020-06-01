import Application from "@enhavo/newsletter/Stats/StatsApplication";
import ActionRegistryPackage from "./registry/action";

Application.getActionRegistry().registerPackage(new ActionRegistryPackage(Application));
Application.getVueLoader().load(() => import("@enhavo/newsletter/Stats/Components/IndexComponent.vue"));