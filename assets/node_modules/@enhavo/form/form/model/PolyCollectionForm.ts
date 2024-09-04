import {ListForm} from "@enhavo/form/form/model/ListForm";
import {Form} from "@enhavo/vue-form/model/Form";

export class PolyCollectionForm extends ListForm
{
    public isOpen: boolean;
    public entryLabels: EntryLabel[];
    public prototypeStorage: string;
    public confirmDelete: boolean;
    public collapsed: boolean;

    public toggleMenu()
    {
        this.isOpen = !this.isOpen;
    }

    public addItem(key?: string): Form
    {
        let prototype = this.findPrototype(key);
        let item = this.createItem(prototype.form, prototype.name);
        this.children.push(item);
        this.updatePosition();
        return item;
    }

    private findPrototype(key: string): Prototype
    {
        let prototypeAware = <PrototypeAware>this.getRoot();
        for (let prototype of prototypeAware.prototypes) {
            if (prototype.parameters.key == key && prototype.storageName == this.prototypeStorage) {
                return prototype;
            }
        }
    }
}

class PrototypeAware extends Form
{
    prototypes: Prototype[];
}

class Prototype
{
    form: Form;
    name: string
    parameters: any
    storageName: string;
}

class EntryLabel
{
    key: string;
    label: string;
}

