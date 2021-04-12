import {Form} from "@enhavo/vue-form/form/Form";
import * as _ from "lodash";
import {FormFactoryInterface} from "@enhavo/vue-form/form/FormFactoryInterface";

export class FormFactory implements FormFactoryInterface
{
    private entries: Entry[] = [];

    createForm(data: any): Form
    {
        let entry = this.getEntry(<string>data.component);

        let form;
        if (entry !== null) {
            form = entry.factory.createForm(data);
        } else {
            form = _.extend(new Form, data);
        }

        for (let i in form.children) {
            form.children[i] = this.createForm(form.children[i]);
        }
        return form;
    }

    registerFactory(component: string, factory: FormFactoryInterface)
    {
        this.entries.push(new Entry(component, factory));
    }

    private getEntry(name: string): Entry
    {
        for (let entry of this.entries) {
            if (entry.name === name) {
                return entry;
            }
        }
        return null;
    }
}

class Entry
{
    name: string;
    factory: FormFactoryInterface;

    constructor(name: string, factory: FormFactoryInterface) {
        this.name = name;
        this.factory = factory;
    }
}
