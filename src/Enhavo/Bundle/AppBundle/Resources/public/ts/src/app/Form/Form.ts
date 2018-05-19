export class FormInitializer
{
    private html: string;

    private element: HTMLElement;

    private converted: boolean = false;

    private released: boolean = false;

    private inserted: boolean = false;

    public setHtml(html: string)
    {
        this.html = html.trim();
    }

    public setElement(element: HTMLElement)
    {
        this.element = element;
    }

    public getElement(): HTMLElement
    {
        return this.element;
    }

    public insertBefore(element: HTMLElement)
    {
        this.insert();
        $(this.element).insertBefore(element);
    }

    public insertAfter(element: HTMLElement)
    {
        this.insert();
        $(this.element).insertAfter(element);
    }

    public convert()
    {
        if(!this.converted) {
            this.converted = true;
            if(this.html) {
                let event = new FormConvertEvent(this.html);
                $('body').trigger('formConvert', event);
                this.html = event.getHtml();
                this.element = $($.parseHTML(this.html)).get(0);
            }
        }
    }

    public release()
    {
        if(!this.inserted) {
            this.insert();
        }

        if(!this.released) {
            this.released = true;
            let event = new FormReleaseEvent(this.element);
            $('body').trigger('formRelease', event);
            this.element = event.getElement();
        }
    }

    public insert()
    {
        if(!this.inserted) {
            this.inserted = true;

            if(!this.converted) {
                this.convert();
            }

            let event = new FormInsertEvent(this.element);
            $('body').trigger('formInsert', event);
            $(document).trigger('gridAddAfter', [this.element]);
            this.element = event.getElement()
        }
    }
}

export class FormListener
{
    public onConvert(callback: (event: FormConvertEvent) => void)
    {
        $('body').on('formConvert', function(event, formEvent: FormConvertEvent) {
            callback(formEvent);
        });
    }

    public onInsert(callback: (event: FormInsertEvent) => void)
    {
        $('body').on('formInsert', function(event, formEvent: FormInsertEvent) {
            callback(formEvent);
        });
    }

    public onRelease(callback: (event: FormReleaseEvent) => void)
    {
        $('body').on('formRelease', function(event, formEvent: FormReleaseEvent) {
            callback(formEvent);
        });
    }
}

export class FormConvertEvent
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

export class FormElementEvent
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

export class FormReleaseEvent extends FormElementEvent
{

}

export class FormInsertEvent extends FormElementEvent
{

}