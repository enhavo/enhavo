import * as $ from "jquery";

export default abstract class FormType
{
    protected $element: JQuery;

    public constructor(element: HTMLElement)
    {
        this.$element = $(element);
        this.init();
    }

    protected abstract init(): void;
}