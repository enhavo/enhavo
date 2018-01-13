import { MediaItem } from 'media/Media';
import * as $ from 'jquery'
import 'cropper';

export class ImageCropper
{
    constructor()
    {
        ImageCropper.initFormListener();
    }

    private static apply(item: MediaItem)
    {
        new ImageCropperItem(item);
    }

    private static initFormListener(): void
    {
        $(document).on('mediaAddItem', function (event, item) {
            ImageCropper.apply(item);
        });
    }
}

export class ImageCropperItem
{
    private item: MediaItem;

    private $itemElement : JQuery;

    private $imageOverlay: JQuery;

    private $cropper: JQuery;

    private loaded = false;

    constructor(item: MediaItem)
    {
        this.item = item;
        this.$itemElement = $(this.item.getElement());
        this.$imageOverlay = this.$itemElement.find('[data-image-cropping-overlay]');
        this.$cropper = this.$imageOverlay.find('[data-image-crop-canvas]');
        this.init();
    }

    private init()
    {
        let self = this;
        this.$itemElement.find('[data-image-cropping-tool]').click(function() {
            self.loadCropper();
        });
    }

    private loadCropper()
    {
        this.loadImage();
        this.$cropper.cropper();
        let self = this;
        this.$cropper.on('built.cropper', function () {
            // Finished loading
            self.$imageOverlay.find('[data-image-crop-canvas-wrapper]').removeClass('loading');
        });
        this.showOverlay();
    }

    private loadImage()
    {
        if(!this.loaded) {
            this.$cropper.attr('src', this.item.getFileUrl());
        }
        this.loaded  = true;
    }

    private showOverlay()
    {
        this.$imageOverlay.show();
        let self = this;

        this.$imageOverlay.find('[data-image-crop-cancel]').click(function (event) {
            event.stopPropagation();
            event.preventDefault();
            self.$imageOverlay.hide();
        });

        this.$imageOverlay.find('[data-image-crop-submit]').click(function (event) {
            event.stopPropagation();
            event.preventDefault();

            self.sendImage(function() {
                self.$imageOverlay.hide();
            });
        });


        this.$imageOverlay.find('[data-image-cropper-action]').click(function (event) {
            event.stopPropagation();
            event.preventDefault();

            let action = $(this).data("image-cropper-action");

            switch (action) {
                case "move-mode":
                    $(this).addClass('selected');
                    self.$imageOverlay.find('[data-image-cropper-action="cropframe-mode"]').removeClass('selected');
                    self.$cropper.cropper('setDragMode', 'move');
                    break;
                case "cropframe-mode":
                    $(this).addClass('selected');
                    self.$imageOverlay.find('[data-image-cropper-action="move-mode"]').removeClass('selected');
                    self.$cropper.cropper('setDragMode', 'crop');
                    break;
                case "zoom-in":
                    self.$cropper.cropper('zoom', '0.1');
                    break;
                case "zoom-out":
                    self.$cropper.cropper('zoom', '-0.1');
                    break;
                case "reset":
                    self.$cropper.cropper('reset');
                    break;
            }
        });
    }

    private sendImage(callback: () => void)
    {
        let canvasData = this.$cropper.cropper('getCropBoxData');
        console.log(canvasData);

        let url = '/media/image/cropper';
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                width: canvasData.width,
                height: canvasData.height,
                left: canvasData.left,
                top: canvasData.top,
            },
            processData: false,
            contentType: false,
            success: function () {
                if(callback) {
                    callback();
                }
            },
        });
    }

    private static dataURItoBlob(dataURI): Blob
    {
        // convert base64/URLEncoded data component to raw binary data held in a string
        let byteString;
        if (dataURI.split(',')[0].indexOf('base64') >= 0)
            byteString = atob(dataURI.split(',')[1]);
        else
            byteString = decodeURI(dataURI.split(',')[1]);

        // separate out the mime component
        let mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

        // write the bytes of the string to a typed array
        let ia = new Uint8Array(byteString.length);
        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }

        return new Blob([ia], {type: mimeString});
    };
}

export let imageCropper = new ImageCropper();