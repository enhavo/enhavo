
export default class FormConvertEvent
{
    private html: string;

    constructor(html: string)
    {
        this.html = html;
    }

    public setHtml(html: string)
    {
        this.html = html;
    }

    public getHtml(): string
    {
        return this.html;
    }
}
