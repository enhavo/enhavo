import Vue from "vue";
import vueForm from "@enhavo/vue-form"
import {Form} from "@enhavo/vue-form/form/Form";

Vue.config.devtools = true;
Vue.config.productionTip = false;
Vue.use(vueForm);

let element = document.getElementById('app');
let form = Form.create(JSON.parse(element.dataset.vue));

new Vue({
    el: element,
    data: {
        form: form
    }
});
