import * as $ from 'jquery'
import 'blueimp-file-upload'
import 'jquery-ui'

interface MediaConfig
{
    multiple: boolean;
    sortable: boolean;
    extensions: Array<UploadExtension>;
}

interface UploadExtension
{
    application: string;
}

export class Media
{
    private $mediaType: JQuery;

    private config: MediaConfig;

    private static mediaTypes: Array<Media> = [];

    private row: MediaRow;

    constructor(mediaType:HTMLElement)
    {
        this.$mediaType = $(mediaType);
        this.config = this.$mediaType.data('media-type');
        this.init();
    }

    public showDropZone()
    {
        this.row.showDropZone();
    }

    public hideDropZone()
    {
        this.row.hideDropZone();
    }

    private init()
    {
        let element:HTMLElement = this.$mediaType.find('[data-media-row]').get(0);
        this.row = new MediaRow(element, this.config);
        this.initFileUpload();

        let self = this;
    }

    private initFileUpload()
    {
        let self = this;

        this.$mediaType.find('[data-file-upload]').fileupload({
            dataType: 'json',
            done: function (event, data) {
                $.each(data.result, function (index, meta) {
                    let item = self.row.createItem(meta);
                    item.updateThumb();
                    self.row.setOrder();
                });
            },
            fail: function (event, data) {
                self.row.showError();
            },
            add: function (event, data) {
                data.submit();
            },
            progressall: function (event, data) {
                let progress = data.loaded / data.total * 100;
                if(progress >= 100) {
                    self.setProgress(0);
                } else {
                    self.setProgress(progress);
                }
            },
            dropZone: this.$mediaType.find('[data-media-drop-zone]')
        });
    }

    private setProgress(value:number)
    {
        this.$mediaType.find('[data-media-progress-bar]').css('width', value + '%');
    }

    static apply(form:HTMLElement|string)
    {
        $(form).find('[data-media-type]').each(function() {
            Media.mediaTypes.push(new Media(this));
        });

        $(document).bind('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
            Media.map(function (mediaType) {
                mediaType.showDropZone();
            });

        });

        $(document).bind('dragleave drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            Media.map(function (mediaType) {
                mediaType.hideDropZone();
            });
        });
    }

    static media(mediaType:HTMLElement): Media|null
    {
        for(let media of Media.mediaTypes) {
            if(media.$mediaType.get(0) === mediaType) {
                return media;
            }
        }
        return null;
    }

    static map(callback: (mediaType:Media) => void)
    {
        for(let media of Media.mediaTypes) {
            callback(media);
        }
    }
}

class MediaRow
{
    private $element: JQuery;

    private items: Array<MediaItem>;

    private config: MediaConfig;

    private openEditItem: MediaItem;

    private index = 0;

    constructor(element:HTMLElement, config:MediaConfig)
    {
        this.config = config;
        this.$element = $(element);
        this.items = [];
        this.initSortable();

        let self = this;
        this.$element.children('[data-media-item]').each(function (index, element) {
            let $element =  $(element);
            let id = $element.find('[data-media-item-id]').data('media-item-id');
            let meta = $element.data('media-meta');
            $element.find('[data-media-item-id]').val(meta.id);
            let item = new MediaItem(<HTMLElement>element, meta, self);
            item.updateThumb();
            self.items.push(item);
            self.index = index;
        });
    }

    updateThumbs() {
        for (let item of this.items) {
            item.updateThumb();
        }
    }

    showDropZone()
    {
        this.$element.css('background-color', '#49a4e5');
    }

    hideDropZone()
    {
        this.$element.css('background-color', '');
    }

    createItem(meta:MediaItemMeta): MediaItem
    {
        this.index++;
        let template = this.$element.parent().find('[data-media-item-template]').text();
        template = template.replace(/__name__/g, this.index.toString());
        let html = $.parseHTML(template)[0];
        $(html).find('[data-media-item-filename]').val(meta.filename);
        $(html).find('[data-media-item-id]').val(meta.id);
        let item = new MediaItem(html, meta, this);
        this.items.push(item);
        this.$element.append(html);
        return item;
    }

    setOrder()
    {

    }

    showError()
    {

    }

    openEdit(item: MediaItem)
    {
        if(this.openEditItem) {
            this.closeEdit();
        }
        item.active();
        let editBox = "<li data-media-edit-container class='media-edit'></li>";
        let html = $.parseHTML(editBox)[0];
        let $html = $(html);
        $html.append(item.getEditElements());
        let afterElement = this.getListAfterElement(item);
        $(afterElement).after(html);
        this.openEditItem = item;
    }

    closeEdit()
    {
        if(this.openEditItem) {
            this.openEditItem.inactive();
            let $edit = this.$element.find('[data-media-edit-container]');
            let html = $edit.children().toArray();
            this.openEditItem.setEditElements(html);
            $edit.remove();
            this.openEditItem = null;
        }
    }

    private getListAfterElement(item: MediaItem): HTMLElement
    {
        let position = 0;
        let width:number = null;
        let rowWidth = this.$element.innerWidth();
        let itemCount = this.$element.children('[data-media-item]').length;
        this.$element.children('[data-media-item]').each(function(index, element) {
            if(width === null) {
                width = $(element).outerWidth();
            }
            if(item.getElement() === element) {
                position = index;
                return false;
            }
            width = $(element).outerWidth();
        });

        let itemPerRows = Math.floor(rowWidth / width);
        let afterPosition = Math.floor(position/itemPerRows)*itemPerRows+itemPerRows;
        if(afterPosition > itemCount) {
            afterPosition = itemCount;
        }
        afterPosition--;
        return this.$element.children('[data-media-item]').get(afterPosition);
    }

    private initSortable()
    {
        if (this.config.multiple && this.config.sortable) {
            let self = this;
            this.$element.sortable({
                delay: 150,
                update: function () {
                    self.$element.children().each(function(index, child) {
                        for (let item of self.items) {
                            if(item.getElement() === child) {
                                item.setOrder(index);
                            }
                        }
                    });
                },
                items: '> li[data-media-item]'
            });
        }
    }
}

class MediaItemMeta
{
    mimeType: string;

    md5Checksum: string;

    id: number;

    extension: string;

    filename: string;

    token: string;
}

class MediaItem
{
    private $element: JQuery;

    private meta: MediaItemMeta;

    private row: MediaRow;

    constructor(element:HTMLElement, meta:MediaItemMeta, row:MediaRow)
    {
        this.$element = $(element);
        this.meta = meta;
        this.row = row;
        this.addHandler();
    }

    private addHandler()
    {
        let self = this;

        this.$element.click(function() {
            self.openEdit();
        });

        this.$element.find('[data-media-item-delete]').click(function(event) {
            event.stopPropagation();
            event.preventDefault();
            self.remove();
        });
    }

    inactive()
    {
        this.$element.removeClass('active');
    }

    active()
    {
        this.$element.addClass('active');
    }

    remove()
    {
        this.$element.remove();
        this.row.setOrder();
    }

    getElement():HTMLElement
    {
        return this.$element.get(0);
    }

    getEditElements():Array<HTMLElement>
    {
        return this.$element.find('[data-media-edit]').children().toArray();
    }

    setEditElements(element:HTMLElement|Array<HTMLElement>)
    {
        return this.$element.find('[data-media-edit]').append(element);
    }

    setOrder(order:number)
    {

    }

    getId()
    {
        return this.meta.id;
    }

    openEdit()
    {
        this.row.openEdit(this);
    }

    closeEdit()
    {
        this.row.closeEdit();
    }

    updateThumb()
    {
        //reset
        this.$element.find('[data-media-thumb]').css('background-image', 'none');
        this.$element.find('[data-media-thumb-icon]').removeClass().addClass('icon');

        switch (this.meta.mimeType) {
            case 'image/png':
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/gif':
                this.$element.find('[data-media-thumb]').css('background-image', 'url('+this.getThumbUrl()+')');
                break;
            case 'video/mpeg':
            case 'video/quicktime':
            case 'video/vnd.vivo':
            case 'video/x-msvideo':
            case 'video/x-sgi-movie':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-video');
                break;
            case 'audio/basic':
            case 'audio/echospeech':
            case 'audio/tsplayer':
            case 'audio/voxware':
            case 'audio/x-aiff':
            case 'audio/x-dspeeh':
            case 'audio/x-midi':
            case 'audio/x-mpeg':
            case 'audio/x-pn-realaudio':
            case 'audio/x-pn-realaudio-plugin':
            case 'audio/x-qt-stream:':
            case 'audio/x-wav':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-audio');
                break;
            case 'application/postscript':
            case 'application/rtf':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-doc-text');
                break;
            case 'application/pdf':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-pdf');
                break;
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document ':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-word');
                break;
            case 'application/msexcel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-excel');
                break;
            case 'application/mspowerpoint':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-powerpoint');
                break;
            case 'application/gzip':
            case 'application/x-compress':
            case 'application/x-compressed':
            case 'application/x-zip-compressed':
            case 'application/x-gtar':
            case 'application/x-shar':
            case 'application/x-tar':
            case 'application/x-ustar':
            case 'application/zip':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-archive');
                break;
            case 'text/css':
            case 'text/html':
            case 'text/javascript':
            case 'text/xml':
            case 'text/x-php':
            case 'application/json':
            case 'application/xhtml+xml':
            case 'application/xml':
            case 'application/x-httpd-php':
            case 'application/x-javascript':
            case 'application/x-latex':
            case 'application/x-php':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-code');
                break;
            default:
                this.$element.find('[data-file-icon]').addClass('icon-doc');
        }
    }

    private getThumbUrl()
    {
        let url = '/file/format/{id}/{format}/{shortMd5Checksum}/{filename}?v={random}';
        url = url.replace('{id}', this.meta.id.toString());
        url = url.replace('{format}', 'enhavoPreviewThumb');
        url = url.replace('{shortMd5Checksum}', this.meta.md5Checksum.substring(0, 6));
        url = url.replace('{filename}', this.meta.filename);
        url = url.replace('{random}', Math.random().toString());
        return url;
    }
}

export default Media;