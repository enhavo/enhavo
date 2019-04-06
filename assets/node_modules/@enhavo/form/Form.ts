import * as $ from 'jquery'

export default class Form
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
