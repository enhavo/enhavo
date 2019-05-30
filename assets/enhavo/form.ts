import Application from "@enhavo/app/Form/FormApplication";
import RegisterAction from './register/action';

RegisterAction(Application);
Application.getForm().load();
Application.getVueLoader().load(() => import("@enhavo/app/Form/Components/FormComponent.vue"));