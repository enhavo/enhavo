import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import ListType from "@enhavo/form/Type/ListType";

export default class ListLoader extends AbstractLoader
{
    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-list]');
        for(element of elements) {
            new ListType(element);
        }
    }
}