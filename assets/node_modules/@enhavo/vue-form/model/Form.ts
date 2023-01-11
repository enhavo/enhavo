import {FormVisitorInterface} from "@enhavo/vue-form/form/FormVisitor";
import {FormEventDispatcherInterface} from "@enhavo/vue-form/form/FormEventDispatcherInterface";
import {ChangeEvent} from "@enhavo/vue-form/event/ChangeEvent";

export class Form
{
    element: HTMLElement;
    parent: Form;
    children: Form[] = [];
    value: string;
    name: string;
    label: string;
    labelFormat: string;
    compound: boolean;
    component: string;
    componentVisitors: string[] = [];
    rowComponent: string;
    id: string;
    labelAttr: object;
    placeholder: string;
    fullName: string;
    required: boolean;
    disabled: boolean;
    attr: object;
    rendered: boolean;
    method: string = null;
    action: string = null;
    visitors: FormVisitorInterface[] = [];
    eventDispatcher: FormEventDispatcherInterface;

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

    public dispatchChange()
    {
        this.eventDispatcher.dispatchEvent(new ChangeEvent(this, this.getValue()), 'change');
    }

    public init()
    {

    }
}
