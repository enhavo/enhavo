import CheckboxType from "@enhavo/form/Type/CheckboxType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import FormRegistry from "@enhavo/app/Form/FormRegistry";

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