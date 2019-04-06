
export default class FormElementEvent
{
    private element: HTMLElement;

    constructor(element: HTMLElement)
    {
        this.element = element;
    }

    public setElement(element: HTMLElement)
    {
        this.element = element;
    }

    public getElement()
    {
        return this.element;
    }
}

