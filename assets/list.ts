import Application from "@enhavo/app/List/ListApplication";
import Component from "@enhavo/app/List/Components/ListApplicationComponent.vue";

Application.getList().load();
Application.getVueLoader().load(Component);
