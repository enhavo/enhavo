import Application from "@enhavo/app/List/ListApplication";
import RegisterAction from './register/action';

RegisterAction(Application);
Application.getList().load();
Application.getVueLoader().load(() => import("@enhavo/app/List/Components/ListApplicationComponent.vue"));
