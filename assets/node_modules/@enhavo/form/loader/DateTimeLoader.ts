import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import DateTimeType from "@enhavo/form/Type/DateTimeType";
import FormRegistry from "@enhavo/app/Form/FormRegistry";

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