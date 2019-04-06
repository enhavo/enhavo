import * as $ from "jquery";

export default abstract class AbstractLoader
{
    protected findElements(element: HTMLElement, selector: string) : HTMLElement[]
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
}