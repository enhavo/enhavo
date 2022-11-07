export class FormData
{
    parent: FormData;
    children: object|any;
    value: string;
    name: string;
    label: string;
    labelFormat: string;
    compound: boolean;
    component: string;
    componentVisitors: string[];
    rowComponent: string;
    id: string;
    labelAttr: object;
    placeholder: string;
    fullName: string;
    required: boolean;
    disabled: boolean;
    attr: object;
    root: boolean;
    rendered: boolean;

    public get(name: string): FormData
    {
        let propertyChain = name.split('.');

        let searchElement: FormData = this;
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
}
