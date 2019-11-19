import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import WeekendDateType from "@enhavo/form/Type/WeekendDateType";

export default class WeekendDateLoader extends AbstractLoader
{
    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-weekend-date-picker]');
        for(element of elements) {
            new WeekendDateType(element);
        }
    }
}