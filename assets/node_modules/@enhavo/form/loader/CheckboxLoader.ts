import CheckboxType from "@enhavo/form/type/CheckboxType";
import AbstractLoader from "@enhavo/form/loader/AbstractLoader";
import FormRegistry from "@enhavo/app/form/FormRegistry";

export default class CheckboxLoader extends AbstractLoader
{
    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element,'input[type=radio],input[type=checkbox]');
        for(element of elements) {
            FormRegistry.registerType(new CheckboxType(element));
        }
    }
}