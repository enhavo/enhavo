import {Controller} from "@hotwired/stimulus";
import vueForm from "@enhavo/vue-form/index";
import {VueFactory} from "@enhavo/app/vue/VueFactory";
import {reactive} from "vue";
import {FormFactory} from "@enhavo/vue-form/form/FormFactory";
import {FormComponentVisitor} from "@enhavo/vue-form/form/FormVisitor";
import {Theme} from "@enhavo/vue-form/form/Theme";
import {HTMLDiff} from "../util/HTMLDiff";


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
