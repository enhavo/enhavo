import FactoryInterface from "@enhavo/core/FactoryInterface";

export class Form
{
    public children: Form[];
    public value: string;
    public name: string;

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
