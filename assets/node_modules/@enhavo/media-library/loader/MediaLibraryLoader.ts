import AbstractLoader from "@enhavo/form/loader/AbstractLoader";
import EventDispatcher from "@enhavo/app/frame/EventDispatcher"
import View from "@enhavo/app/view/View";
import Router from "@enhavo/core/Router";
import UpdatedEvent from "@enhavo/app/frame/event/UpdatedEvent";
import LoadDataEvent from "@enhavo/app/frame/event/LoadDataEvent";
import DataStorageEntry from "@enhavo/app/frame/DataStorageEntry";
import MediaType from "@enhavo/media/type/MediaType"
import MediaItemMeta from "@enhavo/media/type/MediaItemMeta"
import $ from "jquery";

export default class MediaLibraryLoader extends AbstractLoader {
    private currentType: MediaType;
    private readonly eventDispatcher: EventDispatcher;
    private readonly view: View;
    private readonly router: Router;

    constructor(eventDispatcher: EventDispatcher, view: View, router: Router) {
        super();
        this.eventDispatcher = eventDispatcher;
        this.view = view;
        this.router = router;
        this.initListener();
    }

    private initListener() {
        $(document).on('mediaInit', (event, type) => {
            this.initExtension(type);
            this.initUpdateEvent(type);
        });
    }

    private initUpdateEvent(mediaType: MediaType) {
        this.eventDispatcher.on('updated', (event: UpdatedEvent) => {
            this.eventDispatcher.dispatch(new LoadDataEvent('media_library'))
                .then((data: DataStorageEntry) => {
                    if (data) {
                        if (event.id == data.value && event.data != null) {// && mediaType === this.currentType) {
                            this.addItem(mediaType, event.data);
                        }
                    }
                });
        });
    }

    private addItem(mediaType: MediaType, meta: MediaItemMeta) {

        if (mediaType !== this.currentType) {
            return;
        }

        if (!mediaType.getConfig().multiple) {
            mediaType.getRow().clearItems();
        }

        let item = mediaType.getRow().createItem(meta);
        item.updateThumb();
        mediaType.getRow().setOrder();
    }

    public initExtension(mediaType: MediaType): void {
        let url = this.router.generate('enhavo_media_library_file_select', {
            multiple: mediaType.getConfig().multiple ? 1 : 0
        });
        let elements = this.findElements(mediaType.getElement(), '[data-file-media-library]');

        for (let element of elements) {
            $(element).on('click', (event: $.ClickEvent) => {
                event.preventDefault();
                this.view.open(url, 'media_library');
                this.currentType = mediaType;
            });
        }
    }
}
