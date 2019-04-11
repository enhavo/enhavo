import * as $ from "jquery";
import MediaType from "@enhavo/media/Type/MediaType";
import FormType from "@enhavo/form/FormType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import "@enhavo/media/assets/styles/style.scss";
import ImageCropperExtension from "@enhavo/media/Extension/ImageCropperExtension";
import ImageCropperConfiguration from "@enhavo/media/Extension/ImageCropperConfiguration";
import MediaItem from "@enhavo/media/Type/MediaItem";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import CreateEvent from "@enhavo/app/ViewStack/Event/CreateEvent";
import ViewInterface from "@enhavo/app/ViewStack/ViewInterface";
import RemovedEvent from "@enhavo/app/ViewStack/Event/RemovedEvent";

export default class MediaLoader extends AbstractLoader
{
    private isBindDragAndDrop = false;

    private application: ApplicationInterface;

    private cropperViewId: number = null;

    constructor(application: ApplicationInterface) {
        super();
        this.application = application;
    }

    public load(element: HTMLElement, selector: string): FormType[]
    {
        this.bindDragAndDrop();
        this.initImageCropper();
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

    private initImageCropper()
    {
        $(document).on('mediaAddItem', (event, item) => {
            let config = new ImageCropperConfiguration();
            config.openCropper = (media: MediaItem, format: string) => {
                let url = this.application.getRouter().generate('enhavo_media_image_cropper_index', {
                    format: format,
                    token: media.getMeta().token
                });
                this.application.getEventDispatcher().dispatch(new CreateEvent(
                    {
                        label: 'edit',
                        component: 'iframe-view',
                        url: url
                    }, this.application.getView().getId())
                )
                .then((view: ViewInterface) => {
                    this.cropperViewId = view.id;
                }).catch(() => {});
            };
            new ImageCropperExtension(item, config);
        });

        this.application.getEventDispatcher().on('removed', (event: RemovedEvent) => {

        });
    }
}