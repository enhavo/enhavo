
export class Translator
{
    private data: object;

    public setData(data: object)
    {
        this.data = data;
    }

    public trans(value: string, parameters: object = null, translationDomain: string = 'messages')
    {
        if (this.data[translationDomain] && this.data[translationDomain][value]) {
            return this.data[translationDomain][value];
        }
        return value;
    }
}
