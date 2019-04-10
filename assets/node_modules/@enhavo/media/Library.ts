import * as $ from 'jquery'

export default class Library
{
    private $element: JQuery;

    constructor(element:HTMLElement)
    {
        this.$element = $(element);
    }
}