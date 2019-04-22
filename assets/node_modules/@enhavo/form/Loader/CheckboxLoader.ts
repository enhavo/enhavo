import CheckboxType from "@enhavo/form/Type/CheckboxType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";

export default class CheckboxLoader extends AbstractLoader
{
    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element,'input[type=radio],input[type=checkbox]');
        for(element of elements) {
            new CheckboxType(element);
        }
    }
}