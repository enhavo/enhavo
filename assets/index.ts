import Application from "@enhavo/app/Index/IndexApplication";
import IndexComponent from "@enhavo/app/Index/Components/IndexComponent.vue";

Application.getGrid().load();
Application.getVueLoader().load(IndexComponent);
