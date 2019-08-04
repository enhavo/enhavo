import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import DateTimeType from "@enhavo/form/Type/DateTimeType";

export default class DateTimeLoader extends AbstractLoader
{
    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-date-time-picker]');
        for(element of elements) {
            new DateTimeType(element);
        }
    }
}