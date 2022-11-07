import {Controller} from "@hotwired/stimulus";
import vueForm from "@enhavo/vue-form/index";
import {VueFactory} from "@enhavo/app/vue/VueFactory";
import {reactive} from "vue";
import {FormFactory} from "@enhavo/vue-form/form/FormFactory";
import {FormComponentVisitor, FormVisitor} from "@enhavo/vue-form/form/FormVisitor";
import {Theme} from "@enhavo/vue-form/form/Theme";
import {HTMLDiff} from "../util/HTMLDiff";
import {FormListData} from "@enhavo/form/form/data/FormListData";
import {FormData} from "@enhavo/vue-form/data/FormData";


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
        let vueFactory: VueFactory = await  this.application.container.get('@enhavo/app/vue/VueFactory');
        let formFactory: FormFactory = await  this.application.container.get('@enhavo/vue-form/form/FormFactory');

        let theme = this.getTheme();
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

    private getTheme()
    {
        if (this.themeValue) {
            let theme = new Theme()
            theme.addVisitor(new FormComponentVisitor('form-list', 'form-custom-list'));
            theme.addVisitor(new FormVisitor((form: FormData) => {
                return form.component === 'form-list';
            }, (form: FormListData) => {
                form.onMove = (form: FormData) => { console.log(form) }
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
