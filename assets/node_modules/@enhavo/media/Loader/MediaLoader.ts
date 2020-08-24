import * as $ from "jquery";
import MediaType from "@enhavo/media/Type/MediaType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import "@enhavo/media/assets/styles/style.scss";
import ImageCropperExtension from "@enhavo/media/Extension/ImageCropperExtension";
import ImageCropperConfiguration from "@enhavo/media/Extension/ImageCropperConfiguration";
import MediaItem from "@enhavo/media/Type/MediaItem";
import MediaRouter from "@enhavo/media/MediaRouter";
import DownloadExtension from "@enhavo/media/Extension/DownloadExtension";
import View from "@enhavo/app/View/View";
import Router from "@enhavo/core/Router";

export default class MediaLoader extends AbstractLoader
{
    private isBindDragAndDrop = false;

    private readonly view: View;
    private readonly router: Router;

    constructor(view: View, router: Router) {
        super();
        this.view = view;
        this.router = router;

        this.bindDragAndDrop();
        this.initImageCropper();
        this.initDownload();
    }

    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-media-type]');
        for(element of elements) {
            let type = new MediaType(element);
            MediaType.mediaTypes.push(type);
        }
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
                let url = this.router.generate('enhavo_media_image_cropper_index', {
                    format: format,
                    token: media.getMeta().token
                });
                this.view.open(url, 'media-image-cropper')
            };
            new ImageCropperExtension(item, config);
        });
    }

    private initDownload()
    {
        $(document).on('mediaAddItem', (event, item) => {
            new DownloadExtension(new MediaRouter(this.router), item);
        });
    }
}