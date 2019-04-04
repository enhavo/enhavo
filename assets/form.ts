import Application from "@enhavo/app/Form/FormApplication";
import FormComponent from "@enhavo/app/Form/Components/FormComponent.vue";

Application.getForm().load();
Application.getVueLoader().load(FormComponent);