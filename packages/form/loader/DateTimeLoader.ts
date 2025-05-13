import AbstractLoader from "@enhavo/form/loader/AbstractLoader";
import DateTimeType from "@enhavo/form/type/DateTimeType";
import FormRegistry from "@enhavo/app/form/FormRegistry";

export default class DateTimeLoader extends AbstractLoader
{
    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-date-time-picker]');
        for(element of elements) {
            FormRegistry.registerType(new DateTimeType(element));
        }
    }
}