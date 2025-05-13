import {ListForm} from "@enhavo/form/form/model/ListForm";
import {Form} from "@enhavo/vue-form/model/Form";
import {indexOf} from "lodash";

export class PolyCollectionForm extends ListForm
{
    public isOpen: boolean;
    public entryLabels: EntryLabel[];
    public entryKeys: string[];
    public prototypeStorage: string;
    public confirmDelete: boolean;
    public collapsed: boolean;
    public collapsable: boolean = true;

    private collapsedIds: string[] = [];

    public toggleMenu()
    {
        this.isOpen = !this.isOpen;
    }

    public addItem(key?: string, after: Form = null): Form
    {
        let prototype = this.findPrototype(key);
        let item = this.createItem(prototype.form, prototype.name);

        if (after === null) {
            this.children.splice(0, 0, item);
        } else {
            const index = this.children.indexOf(after);
            if (index > -1) {
                this.children.splice(index + 1, 0, item);
            } else {
                this.children.push(item);
            }
        }

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

    public isCollapsed(form: Form): boolean
    {
        return this.collapsedIds.indexOf(form.key) >= 0;
    }

    public collapse(form: Form): void
    {
        this.collapsedIds.push(form.key);
    }

    public uncollapse(form: Form): void
    {
        let index = this.collapsedIds.indexOf(form.key)
        if (index >= 0) {
            this.collapsedIds.splice(index, 1);
        }
    }

    public collapseAll()
    {
        for (let child of this.children) {
            if (!this.isCollapsed(child)) {
                this.collapse(child);
            }
        }
    }

    public uncollapseAll()
    {
        this.collapsedIds = [];
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
    filteredOut: boolean = false;
}

