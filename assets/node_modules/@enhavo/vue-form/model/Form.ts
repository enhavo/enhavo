import {FormVisitorInterface} from "@enhavo/vue-form/form/FormVisitor";
import {FormEventDispatcherInterface} from "@enhavo/vue-form/form/FormEventDispatcherInterface";
import {ChangeEvent} from "@enhavo/vue-form/event/ChangeEvent";

export class Form
{
    element: HTMLElement;
    visible: boolean;
    parent: Form;
    children: Form[] = [];
    value: string;
    name: string;
    label: string|boolean;
    labelFormat: string;
    compound: boolean;
    component: string;
    componentVisitors: string[] = [];
    componentModel: string;
    rowComponent: string;
    id: string;
    labelAttr: object;
    placeholder: string;
    fullName: string;
    required: boolean;
    disabled: boolean;
    attr: object;
    method: string = null;
    action: string = null;
    visitors: FormVisitorInterface[] = [];
    eventDispatcher: FormEventDispatcherInterface;
    errors: FormErrors[] = [];
    type: string;

    public get(name: string): Form
    {
        let propertyChain = name.split('.');

        let searchElement: Form = this;
        for (let property of propertyChain) {
            let hasPropertyChild = false;
            for (let child of searchElement.children) {
                if (child.name == property) {
                    hasPropertyChild = true;
                    searchElement = child;
                    break;
                }
            }

            if (!hasPropertyChild) {
                return null;
            }
        }
        return searchElement;
    }

    public getRoot(): Form
    {
        if (this.getParents().length > 0) {
            let parent = this.getParents()[this.getParents().length - 1];
            return parent;
        }
        return this;
    }

    /**
     * Get parents, the nearest first
     */
    public getParents(): Form[]
    {
        let parents = [];
        let parent = this.parent;
        while (parent) {
            parents.push(parent);
            parent = parent.parent;
        }
        return parents;
    }

    public getValue(): any
    {
        if (this.compound) {
            return undefined;
        }

        return this.value;
    }

    public setValue(value: any)
    {
        if (this.compound) {
            return;
        }

        this.value = value;
        this.dispatchChange();
    }

    public dispatchChange()
    {
        this.eventDispatcher.dispatchEvent(new ChangeEvent(this, this.getValue()), 'change');
    }

    public init()
    {

    }

    public destroy()
    {
        for (let child of this.children) {
            child.destroy();
        }
    }

    public update(recursive: boolean = true)
    {
        let names = [];
        for (let parent of this.getParents().reverse()) {
            names.push(parent.name);
        }
        names.push(this.name);
        this.id = names.join('_');

        let fullName = names.shift();
        for (let name of names) {
            fullName += '[' + name + ']';
        }
        this.fullName = fullName;

        if (recursive) {
            for (let child of this.children) {
                child.update();
            }
        }
    }

    public remove(name: string)
    {
        let element = this.get(name);
        let parent = element.parent;

        parent.children.splice(parent.children.indexOf(element), 1);
    }

    public add(form: Form)
    {
        if (this.get(form.name)) {
            this.remove(form.name);
        }

        this.children.push(form);
        form.parent = this;

        form.update();
    }

    public setElement(element: HTMLElement)
    {
        this.element = element;
        if (element && this.attr) {
            for (let name in this.attr) {
                if (this.attr.hasOwnProperty( name ) ) {
                    if (this.attr[name] === true) {
                        // @ts-ignore setAttribute exists on HTMLElement
                        element.setAttribute(name, name);
                    } else if (this.attr[name] === true) {

                    }
                    // @ts-ignore setAttribute exists on HTMLElement
                    element.setAttribute(name, this.attr[name]);
                }
            }
        }
    }
}

export class FormErrors
{
    message: string;
}
