import { MediaItem } from 'media/Media';
import * as $ from 'jquery'
import * as Cropper from 'cropperjs';

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

    private $itemElement: JQuery;

    private overlay: ImageCropperOverlay;

    constructor(item: MediaItem)
    {
        this.item = item;
        this.$itemElement = $(this.item.getElement());
        let overlayElement = this.$itemElement.find('[data-image-cropping-overlay]').get(0);
        this.overlay = new ImageCropperOverlay(overlayElement, item);
        this.init();
    }

    private init()
    {
        let self = this;
        this.$itemElement.find('[data-image-cropping-tool]').click(function() {
            let format = $(this).data('image-cropping-tool');
            self.overlay.open(format);
        });
    }
}

export class ImageCropperOverlay
{
    private item: MediaItem;

    private $element: JQuery;

    private cropper: Cropper;

    private closeMutex = false;

    private format: string;

    constructor(element: HTMLElement, item: MediaItem)
    {
        this.item = item;
        this.$element = $(element);
    }

    public open(format: string)
    {
        let self = this;
        self.format = format;
        this.showOverlay();
        this.getFormatData(function(formatData) {
            let image = self.createImage();
            self.cropper = new Cropper(image, {
                ready: function () {
                    self.finishLoading();
                    let data = formatData.getData();

                    if(formatData.ratio) {
                        self.cropper.setAspectRatio(formatData.ratio);
                    }

                    if(data !== null) {
                        self.cropper.setData(data);
                    }
                }
            });
        });
    }

    public close()
    {
        this.$element.hide();
        this.$element.find('[data-image-crop-canvas-wrapper] img').remove();
        this.cropper.destroy();
    }

    private finishLoading()
    {
        this.$element.find('[data-image-crop-canvas-wrapper]').removeClass('loading');
    }

    private createImage(): HTMLImageElement
    {
        let image = new Image();
        image.src = this.item.getFileUrl();
        this.$element.find('[data-image-crop-canvas-wrapper]').append(image);
        return image
    }

    private showOverlay()
    {
        this.$element.show();
        let self = this;

        this.$element.find('[data-image-crop-cancel]').click(function (event) {
            event.stopPropagation();
            event.preventDefault();
            self.close();
        });

        this.$element.find('[data-image-crop-submit]').click(function (event) {
            event.stopPropagation();
            event.preventDefault();
            if(self.closeMutex) {
                return;
            }

            self.closeMutex = true;
            self.sendCropData(function() {
                self.close();
                self.closeMutex = false;
            });
        });

        this.$element.find('[data-image-cropper-action]').click(function (event) {
            event.stopPropagation();
            event.preventDefault();

            let action = $(this).data("image-cropper-action");

            switch (action) {
                case "move-mode":
                    $(this).addClass('selected');
                    self.$element.find('[data-image-cropper-action="cropframe-mode"]').removeClass('selected');
                    self.cropper.setDragMode('move');
                    break;
                case "cropframe-mode":
                    $(this).addClass('selected');
                    self.$element.find('[data-image-cropper-action="move-mode"]').removeClass('selected');
                    self.cropper.setDragMode('crop');
                    break;
                case "zoom-in":
                    self.cropper.zoom(0.1);
                    break;
                case "zoom-out":
                    self.cropper.zoom(-0.1);
                    break;
                case "reset":
                    self.cropper.reset();
                    break;
            }
        });
    }

    private sendCropData(callback: () => void)
    {
        let data = this.cropper.getData();
        let url = '/media/image/' + this.item.getMeta().token + '/' + this.format + '/cropper/crop';
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                height: data.height,
                width: data.width,
                x: data.x,
                y: data.y,
            },
            success: function () {
                if(callback) {
                    callback();
                }
            },
        });
    }

    private getFormatData(callback: (data: FormatData) => void)
    {
        let url = '/media/image/' + this.item.getMeta().token + '/' + this.format + '/cropper/data';
        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
                let formatData = new FormatData();
                formatData.width = data.width;
                formatData.height = data.height;
                formatData.x = data.x;
                formatData.y = data.y;
                formatData.ratio = data.ratio;
                callback(formatData);
            },
        });
    }
}

class FormatData
{
    x: number = null;

    y: number = null;

    width: number = null;

    height: number = null;

    rotate: number = 0;

    scaleX: number = 1;

    scaleY: number = 1;

    ratio: number = null;

    getData() {
        if(this.x !== null && this.y !== null && this.width !== null && this.height !== null) {
            return {
                x: this.x,
                y: this.y,
                width: this.width,
                height: this.height,
                scaleX: this.scaleX,
                scaleY: this.scaleY,
                rotate: this.rotate,
            }
        }
        return null;
    }
}

export let imageCropper = new ImageCropper();