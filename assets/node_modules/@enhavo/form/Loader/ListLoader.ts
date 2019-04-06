import FormType from "@enhavo/form/FormType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import ListType from "@enhavo/form/Type/ListType";

export default class ListLoader extends AbstractLoader
{
    public load(element: HTMLElement, selector: string): FormType[]
    {
        let data = [];
        let elements = this.findElements(element, selector);
        for(element of elements) {
            data.push(new ListType(element));
        }
        return data;
    }
}