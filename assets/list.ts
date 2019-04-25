import Application from "@enhavo/app/List/ListApplication";
Application.getList().load();
Application.getVueLoader().load(() => import("@enhavo/app/List/Components/ListApplicationComponent.vue"));
