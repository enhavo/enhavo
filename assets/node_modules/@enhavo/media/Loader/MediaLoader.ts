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
import LoadDataEvent from "@enhavo/app/ViewStack/Event/LoadDataEvent";
import SaveDataEvent from "@enhavo/app/ViewStack/Event/SaveDataEvent";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";
import ExistsEvent from "@enhavo/app/ViewStack/Event/ExistsEvent";
import ExistsData from "@enhavo/app/ViewStack/ExistsData";

export default class MediaLoader extends AbstractLoader
{
    private isBindDragAndDrop = false;

    private application: ApplicationInterface;

    private static cropperViewId: number = null;

    constructor(application: ApplicationInterface) {
        super();
        this.application = application;

        let cropperStorageKey = 'media-image-cropper-' + this.application.getView().getId();
        this.application.getEventDispatcher().dispatch(new LoadDataEvent(cropperStorageKey))
            .then((data: CropperStorageData) => {
                if(data.id) {
                    MediaLoader.cropperViewId = data.id;
                }
            });
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

                if(MediaLoader.cropperViewId == null) {
                    this.openCropperView(media, format)
                } else {
                    this.application.getEventDispatcher().dispatch(new ExistsEvent(MediaLoader.cropperViewId)).then((data: ExistsData) => {
                        if(data.exists) {
                            this.application.getEventDispatcher().dispatch(new CloseEvent(MediaLoader.cropperViewId))
                                .then(() => {
                                    this.openCropperView(media, format)
                                })
                        } else {
                            this.openCropperView(media, format)
                        }
                    });
                }
            };
            new ImageCropperExtension(item, config);
        });
    }

    private openCropperView(media: MediaItem, format: string)
    {
        let cropperStorageKey = 'media-image-cropper-' + this.application.getView().getId();

        let url = this.application.getRouter().generate('enhavo_media_image_cropper_index', {
            format: format,
            token: media.getMeta().token
        });

        this.application.getEventDispatcher().dispatch(new CreateEvent(
            {
                label: this.application.getTranslator().trans('enhavo_media.cropping'),
                component: 'iframe-view',
                url: url
            }, this.application.getView().getId())
        )
            .then((view: ViewInterface) => {
                MediaLoader.cropperViewId = view.id;
                this.application.getEventDispatcher().dispatch(new SaveDataEvent(cropperStorageKey, {
                    id: view.id
                }));
            }).catch(() => {});
    }
}

interface CropperStorageData
{
    id: number;
}