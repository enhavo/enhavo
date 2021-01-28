import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import DateType from "@enhavo/form/Type/DateType";
import FormRegistry from "@enhavo/app/Form/FormRegistry";

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