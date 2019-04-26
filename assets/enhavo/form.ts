import Application from "@enhavo/app/Form/FormApplication";
Application.getForm().load();
Application.getVueLoader().load(() => import("@enhavo/app/Form/Components/FormComponent.vue"));