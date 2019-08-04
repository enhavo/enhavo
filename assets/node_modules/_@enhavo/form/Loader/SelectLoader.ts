import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import SelectType from "@enhavo/form/Type/SelectType";

export default class SelectLoader extends AbstractLoader
{
    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, 'select');
        for(element of elements) {
           new SelectType(element);
        }
    }
}