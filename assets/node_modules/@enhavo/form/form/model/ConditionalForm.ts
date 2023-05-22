import {Form} from "@enhavo/vue-form/model/Form";
import {FormFactory} from "@enhavo/vue-form/form/FormFactory";

export class ConditionalForm extends Form
{
    public prototypes: PrototypeEntry[];
    public prototypeName: string;
    public entryKey: string;

    constructor(
        protected formFactory: FormFactory,
    ) {
        super();
    }

    public setEntry(key: string)
    {
        this.get('key').value = key;

        let prototype = this.getPrototype(key);

        if (prototype) {
            let form = JSON.parse(JSON.stringify(prototype));
            form = this.formFactory.create(form, this.getRoot().visitors, this);
            form.name = 'conditional';
            this.updateFullName(form);
            this.updateId(form);
            this.replace(form);
        }
    }

    private getPrototype(key: string): Form
    {
        for (let entry of this.prototypes) {
            if (entry.key == key) {
                return entry.form;
            }
        }

        return null;
    }

    private replace(form: Form)
    {
        let child = this.get('conditional');
        this.children[this.children.indexOf(child)] = form;
    }

    protected updateId(form: Form)
    {
        let names = [];
        for (let parent of form.getParents().reverse()) {
            names.push(parent.name);
        }
        names.push(form.name);
        form.id = names.join('_');

        for (let child of form.children) {
            this.updateId(child);
        }
    }

    protected updateFullName(form: Form)
    {
        let parents = form.getParents().reverse();
        let fullName = parents.shift().name;
        for (let parent of parents) {
            fullName += '[' + parent.name + ']';
        }
        fullName += '[' + form.name + ']';

        form.fullName = fullName;

        for (let child of form.children) {
            this.updateFullName(child);
        }
    }
}

export class PrototypeEntry
{
    key: string;
    form: Form;
}
