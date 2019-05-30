import Application from "@enhavo/app/Index/IndexApplication";
import RegisterAction from './register/action';

RegisterAction(Application);
Application.getGrid().load();
Application.getVueLoader().load(() => import("@enhavo/app/Index/Components/IndexComponent.vue"));
