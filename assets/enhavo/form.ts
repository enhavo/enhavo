import Application from "@enhavo/app/Form/FormApplication";
import ActionRegistryPackage from './registry/action';
import ModalRegistryPackage from './registry/modal';

Application.getActionRegistry().registerPackage(new ActionRegistryPackage(Application));
Application.getModalRegistry().registerPackage(new ModalRegistryPackage(Application));
Application.getForm().load();
Application.getVueLoader().load(() => import("@enhavo/app/Form/Components/FormComponent.vue"));