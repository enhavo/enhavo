import Application from "@enhavo/app/Index/IndexApplication";
import ActionRegistryPackage from "./registry/action";
import ModalRegistryPackage from "./registry/modal";
import BatchRegistryPackage from "./registry/batch";
import ColumnRegistryPackage from "./registry/column";

Application.getActionRegistry().registerPackage(new ActionRegistryPackage(Application));
Application.getModalRegistry().registerPackage(new ModalRegistryPackage(Application));
Application.getBatchRegistry().registerPackage(new BatchRegistryPackage(Application));
Application.getColumnRegistry().registerPackage(new ColumnRegistryPackage(Application));
Application.getGrid().load();
Application.getVueLoader().load(() => import("@enhavo/app/Index/Components/IndexComponent.vue"));
