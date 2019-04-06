import FormType from "@enhavo/form/FormType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import WysiwygType from "@enhavo/form/Type/WysiwygType";

export default class WysiwygLoader extends AbstractLoader
{
    public load(element: HTMLElement, selector: string): FormType[]
    {
        let data = [];
        let elements = this.findElements(element, selector);
        for(element of elements) {
            data.push(new WysiwygType(element));
        }
        return data;
    }
}