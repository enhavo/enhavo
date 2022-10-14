import {Controller} from "@hotwired/stimulus";
import {Form} from "@enhavo/vue-form/form/Form";
import vueForm from "@enhavo/vue-form/index";
import {VueFactory} from "@enhavo/app/vue/VueFactory";
import {reactive} from "vue";

export default class extends Controller
{
    private formValue: any

    static values = {
        form: Object
    }

    connect()
    {
        this.application.container.get('@enhavo/app/vue/VueFactory').then((vueFactory: VueFactory) => {
            let form = Form.create(this.formValue);

            console.log(form)

            const app = vueFactory.createApp({}, {
                form: reactive(form)
            });

            app.use(vueForm);
            app.mount(this.element);
        });
    }
}
