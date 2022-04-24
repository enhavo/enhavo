import * as Vue from "vue";
import vueForm from "@enhavo/vue-form"
import {Form} from "@enhavo/vue-form/form/Form";
import FormComponent from "../components/FormComponent.vue";

let element = document.getElementById('app');
let form = Form.create(JSON.parse(element.dataset.vue));

const app = Vue.createApp(FormComponent, {
    form: form
});
app.use(vueForm);
app.mount('#app');
