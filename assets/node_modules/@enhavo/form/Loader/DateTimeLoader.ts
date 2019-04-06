import FormType from "@enhavo/form/FormType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import DateTimeType from "@enhavo/form/Type/DateTimeType";

export default class DateTimeLoader extends AbstractLoader
{
    public load(element: HTMLElement, selector: string): FormType[]
    {
        let data = [];
        let elements = this.findElements(element, selector);
        for(element of elements) {
            data.push(new DateTimeType(element));
        }
        return data;
    }
}