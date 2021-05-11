import FactoryInterface from "@enhavo/core/FactoryInterface";

export class Form
{
    public children: Form[];
    public value: string;
    public name: string;
    public label: string;
    public labelFormat: string;
    public compound: string;
    public id: string;
    public labelAttr: string;
    public placeholder: string;
    public fullName: string;
    public required: boolean;
    public disabled: boolean;
    public attr: object;

    public get(name: string): Form
    {
        for (let child of this.children) {
            if (child.name === name) {
                return child;
            }
        }
        return null;
    }
}
