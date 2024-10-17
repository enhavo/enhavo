import {VueFactory} from "@enhavo/app/vue/VueFactory";
import {FormFactory} from "@enhavo/vue-form/form/FormFactory";
import {reactive} from "vue";
import {AbstractController} from "./AbstractController";

export default class extends AbstractController
{
    public static values = {
        'form': Object,
        'component': String,
    }

    private formValue: object;
    private componentValue: string;

    public connect() {
        this.init().then(() => {

        });
    }

    async init()
    {
        const vueFactory: VueFactory = await this.application.container.get('@enhavo/app/vue/VueFactory');

        const formFactory: FormFactory = await this.application.container.get('@enhavo/vue-form/form/FormFactory');
        let form = formFactory.create(this.formValue);

        let component = new Component({form: null});

        if ((<HTMLElement>this.element).tagName.toLowerCase() === 'form') {
            form.element = this.element;
        }

        if (this.componentValue) {
            component = vueFactory.getComponent(this.componentValue);
        }

        const app = vueFactory.createApp(component, {
            form: reactive(form),
        });

        app.mount(this.element);
    }
}

class Component
{
    public props: object;

    constructor(props: object) {
        this.props = props;
    }
}
