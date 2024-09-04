import {FormVisitorInterface} from "@enhavo/vue-form/form/FormVisitor";
import {FormEventDispatcherInterface} from "@enhavo/vue-form/form/FormEventDispatcherInterface";
import {ChangeEvent} from "@enhavo/vue-form/event/ChangeEvent";

export class Form
{
    element: HTMLElement;
    key: string;
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
    widgetComponent: string;
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

    private morphStartValue: any;

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
                let path = [];
                for (let parent of this.getParents()) {
                    path.push(parent.name);
                }
                path.push(this.name);
                throw 'Form child "'+name+'" does not exist in "'+path.join('.')+'".'
            }
        }
        return searchElement;
    }

    public has(name: string): boolean
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
                return false;
            }
        }
        return true;
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

    public getModelValue(): any
    {
        if (this.compound) {
            const value = {};
            for (let child of this.children) {
                value[child.name] = child.getModelValue();
            }
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

    public morphStart()
    {
        if (!this.compound) {
            this.morphStartValue = this.value;
        } else {
            for (let child of this.children) {
                child.morphStart();
            }
        }
    }

    public morphMerge(form: Form)
    {
        if (!this.compound) {
            if (this.morphStartValue == this.value) {
                this.value = form.value;
            }
            this.errors = form.errors;
        } else {
            for (let formChild of form.children) {
                if (this.has(formChild.name)) {
                    this.get(formChild.name).morphMerge(formChild);
                } else {
                    this.add(formChild);
                }
            }

            for (let child of this.children) {
                if (!form.has(child.name)) {
                    this.remove(child.name);
                    child.destroy();
                }
            }
        }
    }

    public morphFinish()
    {
        this.morphStartValue = undefined;

        for (let child of this.children) {
            child.morphFinish();
        }

        this.update();
    }
}

export class FormErrors
{
    message: string;
}
