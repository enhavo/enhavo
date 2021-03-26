import AbstractLoader from "@enhavo/form/loader/AbstractLoader";
import SelectType from "@enhavo/form/type/SelectType";
import FormRegistry from "@enhavo/app/form/FormRegistry";

export default class SelectLoader extends AbstractLoader
{
    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, 'select');
        for(element of elements) {
            FormRegistry.registerType(new SelectType(element));
        }
    }
}