import * as $ from 'jquery'

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
        this.release();
    }

    public insertAfter(element: HTMLElement)
    {
        this.insert();
        $(this.element).insertAfter(element);
        this.release();
    }

    public append(element: HTMLElement)
    {
        this.insert();
        $(element).append(this.element);
    }

    public convert()
    {
        if(!this.converted) {
            this.converted = true;
            if(this.html) {
                this.html = FormDispatcher.dispatchConvert(this.html).getHtml();
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
            this.element = FormDispatcher.dispatchRelease(this.element).getElement();
        }
    }

    public init()
    {
        this.release();
    }

    public insert()
    {
        if(!this.inserted) {
            this.inserted = true;

            if(!this.converted) {
                this.convert();
            }

            this.element = FormDispatcher.dispatchInsert(this.element).getElement();
        }
    }
}

export class FormDispatcher
{
    public static dispatchMove(element: HTMLElement)
    {
        let event = new FormElementEvent(element);
        $('body').trigger('formInsert', event);
        return event;
    }

    public static dispatchDrop(element: HTMLElement)
    {
        let event = new FormElementEvent(element);
        $('body').trigger('formInsert', event);
        return event;
    }

    public static dispatchConvert(element: string)
    {
        let event = new FormConvertEvent(element);
        $('body').trigger('formConvert', event);
        return event;
    }

    public static dispatchInsert(element: HTMLElement)
    {
        let event = new FormInsertEvent(element);
        $('body').trigger('formInsert', event);
        return event;
    }

    public static dispatchRelease(element: HTMLElement)
    {
        let event = new FormReleaseEvent(element);
        $('body').trigger('formRelease', event);
        return event;
    }
}

export class FormListener
{
    public static onConvert(callback: (event: FormConvertEvent) => void)
    {
        $('body').on('formConvert', function(event, formEvent: FormConvertEvent) {
            callback(formEvent);
        });
    }

    public static onInsert(callback: (event: FormInsertEvent) => void)
    {
        $('body').on('formInsert', function(event, formEvent: FormInsertEvent) {
            callback(formEvent);
        });
    }

    public static onRelease(callback: (event: FormReleaseEvent) => void)
    {
        $('body').on('formRelease', function(event, formEvent: FormReleaseEvent) {
            callback(formEvent);
        });
    }

    public static onMove(callback: (event: FormReleaseEvent) => void)
    {
        $('body').on('formMove', function(event, formEvent: FormElementEvent) {
            callback(formEvent);
        });
    }

    public static onDrop(callback: (event: FormReleaseEvent) => void)
    {
        $('body').on('formDrop', function(event, formEvent: FormElementEvent) {
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

class Form
{
    protected $element: JQuery;

    public constructor(element: HTMLElement)
    {
        this.$element = $(element);
        this.init();
    }

    private init()
    {

    }
}

export abstract class FormElement
{
    protected $element: JQuery;

    public constructor(element: HTMLElement)
    {
        this.$element = $(element);
        this.init();
    }

    protected static findElements(element: HTMLElement, selector: string) : HTMLElement[]
    {
        let data = [];
        
        if($(element).is(selector)) {
            data.push(element)
        }
        
        $(element).find(selector).each(function(index: number, element: HTMLElement) {
            data.push(element);
        });
        
        return data;
    }

    protected abstract init();

    public abstract apply(element: HTMLElement, form: Form) : FormElement[];
}