import {Controller} from "@hotwired/stimulus";
import vueForm from "@enhavo/vue-form/index";
import {VueFactory} from "@enhavo/app/vue/VueFactory";
import {reactive} from "vue";
import {FormFactory} from "@enhavo/vue-form/form/FormFactory";
import {FormComponentVisitor, FormVisitor} from "@enhavo/vue-form/form/FormVisitor";
import {Theme} from "@enhavo/vue-form/form/Theme";
import {HTMLDiff} from "../util/HTMLDiff";
import {Form} from "@enhavo/vue-form/model/Form";
import {ListForm} from "@enhavo/form/form/model/ListForm";
import {FormEventDispatcher} from "@enhavo/vue-form/form/FormEventDispatcher";
import {MoveEvent} from "@enhavo/form/form/event/MoveEvent";

export default class extends Controller
{
    private formValue: any
    private themeValue: boolean

    static values = {
        form: Object,
        theme: Boolean
    }

    async connect()
    {
        let vueFactory: VueFactory = await this.application.container.get('@enhavo/app/vue/VueFactory');
        let formFactory: FormFactory = await this.application.container.get('@enhavo/vue-form/form/FormFactory');
        let formEventDispatcher: FormEventDispatcher = await this.application.container.get('@enhavo/vue-form/form/FormEventDispatcher');

        let theme = this.getTheme(formEventDispatcher);

        let form = formFactory.create(this.formValue, theme);

        console.log(form);

        const app = vueFactory.createApp({}, {
            form: reactive(form)
        });

        app.use(vueForm);
        app.mount(this.element);

        window.setTimeout(() => {
            this.diff();
        }, 500);
    }

    private getTheme(formEventDispatcher: FormEventDispatcher)
    {
        if (this.themeValue) {
            let theme = new Theme()
            theme.addVisitor(new FormComponentVisitor('form-list', 'form-custom-list'));
            theme.addVisitor(new FormVisitor((form: Form) => {
                return form.component === 'form-list';
            }, (form: ListForm) => {
                formEventDispatcher.addListener('move', (event: MoveEvent) => {
                    console.log(event.form)
                });
            }));
            return theme;
        }
        return null;
    }

    private diff()
    {
        if (!document.getElementById('one') || !document.getElementById('output')) {
            return;
        }

        let one = document.getElementById('one').childNodes[0];
        let two = document.getElementById('two').childNodes[1];
        let output = document.getElementById('output');

        console.log(one);
        console.log(two);

        output.innerText = HTMLDiff.isEqual(one, two) ? 'Success' : 'Failure'
    }
}
