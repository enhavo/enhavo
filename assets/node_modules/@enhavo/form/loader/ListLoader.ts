import AbstractLoader from "@enhavo/form/loader/AbstractLoader";
import ListType from "@enhavo/form/type/ListType";
import "@enhavo/form/assets/styles/form.scss";
import FormRegistry from "@enhavo/app/form/FormRegistry";

export default class ListLoader extends AbstractLoader
{
    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-list]');
        for(element of elements) {
            FormRegistry.registerType(new ListType(element));
        }
    }
}