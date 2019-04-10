import * as $ from "jquery";
import MediaType from "@enhavo/media/Type/MediaType";
import FormType from "@enhavo/form/FormType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import "@enhavo/media/assets/styles/style.scss";

export default class MediaLoader extends AbstractLoader
{
    private isBindDragAndDrop = false;

    public load(element: HTMLElement, selector: string): FormType[]
    {
        this.bindDragAndDrop();
        let data = [];
        let elements = this.findElements(element, selector);
        for(element of elements) {
            let type = new MediaType(element);
            data.push(type);
            MediaType.mediaTypes.push(type);
        }
        return data;
    }

    private bindDragAndDrop()
    {
        if(!this.isBindDragAndDrop)
        {
            $(document).bind('dragover', function (e) {
                e.preventDefault();
                e.stopPropagation();
                MediaType.map(function (mediaType) {
                    mediaType.showDropZone();
                });
            });

            $(document).bind('dragleave drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                MediaType.map(function (mediaType) {
                    mediaType.hideDropZone();
                });
            });

            this.isBindDragAndDrop = true;
        }
    }
}