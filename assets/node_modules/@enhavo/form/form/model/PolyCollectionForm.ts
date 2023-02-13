import {ListForm} from "@enhavo/form/form/model/ListForm";
import {Form} from "@enhavo/vue-form/model/Form";

export class PolyCollectionForm extends ListForm
{
    public isOpen: boolean;
    public entryLabels: Object;
    public prototypeStorage: string;
    public confirmDelete: boolean;
    public collapsed: boolean;

    public toggleMenu()
    {
        this.isOpen = !this.isOpen;
    }

    public addItem(key?: string): Form
    {
        let item = this.createItem(this.getPrototypeEntry(key), this.getPrototypeEntryName(key));
        this.children.push(item);
        this.updatePosition();
        return item;
    }

    private getPrototypeEntry(key: string): Form
    {
        return this.findPrototype(key).form;
    }

    private getPrototypeEntryName(key: string): string
    {
        return this.findPrototype(key).name;
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
