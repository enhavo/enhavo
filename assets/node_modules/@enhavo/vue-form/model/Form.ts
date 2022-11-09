import {RootForm} from "@enhavo/vue-form/model/RootForm";

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

    public getRoot(): RootForm|Form
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
}
