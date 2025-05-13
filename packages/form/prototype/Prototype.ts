export default class Prototype
{
    private name: string;
    private label: string;
    private template: string;
    private storageName: string;
    private parameters: object;

    constructor(name: string, label: string, template: string, storageName: string, parameters: object)
    {
        this.name = name;
        this.label = label;
        this.template = template;
        this.storageName = storageName;
        this.parameters = parameters;
    }

    public getName()
    {
        return this.name;
    }

    public getLabel()
    {
        return this.label;
    }

    public getTemplate()
    {
        return this.template;
    }

    public getStorageName()
    {
        return this.storageName;
    }

    public getParameters()
    {
        return this.parameters;
    }

    public getParameter(key: string)
    {
        return this.parameters[key];
    }
}
