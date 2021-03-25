import AbstractLoader from "@enhavo/form/loader/AbstractLoader";
import DateType from "@enhavo/form/type/DateType";
import FormRegistry from "@enhavo/app/form/FormRegistry";

export default class DateLoader extends AbstractLoader
{
    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-date-picker]');
        for(element of elements) {
            FormRegistry.registerType(new DateType(element));
        }
    }
}