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
            if (searchElement.children.hasOwnProperty(property)) {
                searchElement = searchElement.children[property];
            } else {
                return null;
            }
        }
        return searchElement;
    }
}
