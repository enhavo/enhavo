import {Controller} from "@hotwired/stimulus";
import {Form} from "@enhavo/vue-form/form/Form";
import vueForm from "@enhavo/vue-form/index";
import {VueFactory} from "@enhavo/app/vue/VueFactory";
import {reactive} from "vue";
import {Theme, ThemeComponentVisitor, ThemeVisitor} from "@enhavo/vue-form/form/Theme";

export default class extends Controller
{
    private formValue: any
    private themeValue: boolean

    static values = {
        form: Object,
        theme: Boolean
    }

    connect()
    {
        this.application.container.get('@enhavo/app/vue/VueFactory').then((vueFactory: VueFactory) => {
            let form = Form.create(this.formValue);

            let theme = this.getTheme();
            if (theme) {
                form.addTheme(theme);
            }

            console.log(form)

            const app = vueFactory.createApp({}, {
                form: reactive(form)
            });

            app.use(vueForm);
            app.mount(this.element);
        });
    }

    private getTheme()
    {
        if (this.themeValue) {
            let theme = new Theme()
            theme.addVisitor(new ThemeComponentVisitor('form-list', 'form-custom-list'));
            return theme;
        }
        return null;
    }
}
