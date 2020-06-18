export default class PolyCollectionPrototype
{
    private key: string;
    private label: string;
    private template: string;

    constructor(key: string, label: string, template: string)
    {
        this.key = key;
        this.label = label;
        this.template = template;
    }

    public getKey()
    {
        return this.key;
    }

    public getLabel()
    {
        return this.label;
    }

    public getTemplate()
    {
        return this.template;
    }
}
