
export default class Translator
{
    private data: object;

    public setData(data: object)
    {
        this.data = data;
    }

    public trans(value: string, parameters?: object)
    {
        if(this.data[value]) {
            return this.data[value];
        }
        return value;
    }
}

