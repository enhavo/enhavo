import Application from "@enhavo/app/Index/IndexApplication";
Application.getGrid().load();
Application.getVueLoader().load(() => import("@enhavo/app/Index/Components/IndexComponent.vue"));
