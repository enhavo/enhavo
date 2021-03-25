import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import WysiwygType from "@enhavo/form/Type/WysiwygType";
import * as tinymce from "tinymce";
import FormRegistry from "@enhavo/app/Form/FormRegistry";

export default class WysiwygLoader extends AbstractLoader
{
    public release(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-wysiwyg]');
        for(element of elements) {
            FormRegistry.registerType(new WysiwygType(element));
        }
    }

    public move(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-wysiwyg]');
        for(element of elements) {
            tinymce.EditorManager.remove('#'+element.id);
        }
    }

    public remove(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-wysiwyg]');
        for(element of elements) {
            tinymce.EditorManager.remove('#'+element.id);
        }
    }

    public drop(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-wysiwyg]');
        for(element of elements) {
            FormRegistry.registerType(new WysiwygType(element));
        }
    }
}