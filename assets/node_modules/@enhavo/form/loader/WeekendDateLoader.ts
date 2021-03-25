import AbstractLoader from "@enhavo/form/loader/AbstractLoader";
import WeekendDateType from "@enhavo/form/type/WeekendDateType";
import FormRegistry from "@enhavo/app/form/FormRegistry";

export default class WeekendDateLoader extends AbstractLoader
{
    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-weekend-date-picker]');
        for(element of elements) {
            FormRegistry.registerType(new WeekendDateType(element));
        }
    }
}