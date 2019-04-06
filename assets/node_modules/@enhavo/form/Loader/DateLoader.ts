import FormType from "@enhavo/form/FormType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import DateType from "@enhavo/form/Type/DateType";

export default class DateLoader extends AbstractLoader
{
    public load(element: HTMLElement, selector: string): FormType[]
    {
        let data = [];
        let elements = this.findElements(element, selector);
        for(element of elements) {
            data.push(new DateType(element));
        }
        return data;
    }
}