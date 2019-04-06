import FormType from "@enhavo/form/FormType";
import CheckboxType from "@enhavo/form/Type/CheckboxType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";

export default class CheckboxLoader extends AbstractLoader
{
    public load(element: HTMLElement, selector: string): FormType[]
    {
        let data = [];
        let elements = this.findElements(element, selector);
        for(element of elements) {
            data.push(new CheckboxType(element));
        }
        return data;
    }
}