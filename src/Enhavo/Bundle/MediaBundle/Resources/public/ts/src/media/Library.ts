import * as $ from 'jquery'

export class Library
{
    private $element: JQuery;

    constructor(element:HTMLElement)
    {
        this.$element = $(element);
    }
}