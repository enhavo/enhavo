import {FormData} from "@enhavo/vue-form/data/FormData";

export class Theme
{
    private components: object = {};
    private callbacks: Array<(form: FormData) => void> = [];

    component(name: string, component: any)
    {
        this.components[name] = component;
    }

    forEach(callback: (form: FormData) => void)
    {
        this.callbacks.push(callback);
    }

    apply(form: FormData)
    {
        this.updateComponent(form);
        for (let key in form.children) {
            if (form.children.hasOwnProperty(key)) {
                this.apply(form.children[key]);
            }
        }
    }

    private updateComponent(form: FormData)
    {
        if (this.components.hasOwnProperty(form.component)) {
            form.component = this.components[form.component];
        }

        if (this.components.hasOwnProperty(form.rowComponent)) {
            form.rowComponent = this.components[form.rowComponent];
        }

        for (let callback of this.callbacks) {
            callback(form);
        }
    }
}
