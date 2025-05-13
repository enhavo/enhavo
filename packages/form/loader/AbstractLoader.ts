import $ from "jquery";
import LoaderInterface from "@enhavo/app/form/LoaderInterface";

export default abstract class AbstractLoader implements LoaderInterface
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

    insert(element: HTMLElement): void
    {

    }

    release(element: HTMLElement): void
    {

    }

    drop(element: HTMLElement): void
    {

    }

    move(element: HTMLElement): void
    {

    }

    remove(element: HTMLElement): void
    {

    }
}