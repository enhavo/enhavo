import Router from "@enhavo/core/Router";
import MediaData from "@enhavo/media/media-library/MediaData";
import MediaItem from "@enhavo/media/media-library/MediaItem";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import axios from 'axios';
import * as _ from "lodash";
import View from "@enhavo/app/view/View";
import Translator from "@enhavo/core/Translator";
import RemovedEvent from "@enhavo/app/view-stack/event/RemovedEvent";
import UpdatedEvent from "@enhavo/app/view-stack/event/UpdatedEvent";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

export default class MediaLibrary
{
    public data: MediaData;
    private readonly router: Router;
    private readonly eventDispatcher: EventDispatcher;
    private readonly view: View;
    private readonly translator: Translator;
    private readonly componentRegistry: ComponentRegistryInterface;

    constructor(data: MediaData, router: Router, eventDispatcher: EventDispatcher, view: View, translator: Translator, componentRegistry: ComponentRegistryInterface)
    {
        _.extend(data, new MediaData());
        this.data = data;
        this.eventDispatcher = eventDispatcher;
        this.view = view;
        this.router = router;
        this.translator = translator;
        this.componentRegistry = componentRegistry;
    }

    init() {
        this.refresh();

        this.eventDispatcher.on('removed', (event: RemovedEvent) => {
            if(event.id == this.data.editView) {
                this.data.editView = null;
            }
        });

        this.eventDispatcher.on('updated', (event: UpdatedEvent) => {
            if(event.id == this.data.editView) {
                this.refresh();
            }
        });

        this.data = this.componentRegistry.registerData(this.data);
        this.componentRegistry.registerStore('mediaLibrary', this);
    }

    setProgress(value: number)
    {
        this.data.progress = value;
    }

    refresh()
    {
        this.loading();
        let url = this.router.generate('enhavo_media_library_list', {
            page: this.data.page
        });

        axios
            .get(url, {params: []})
            .then(response => {
                this.data.items = this.createRowData(response.data.resources);
                this.data.count = parseInt(response.data.pages.count);
                this.data.page = parseInt(response.data.pages.page);
                this.loaded();
            })
            .catch(error => {
                this.view.alert('error');
            })
            .then(() => {
                this.data.loading = false;
            })
    }

    loading() {
        this.view.loading();
        this.data.loading = true;
    }

    loaded() {
        this.view.loaded();
        this.data.loading = false;
    }

    private createRowData(objects: object[]): MediaItem[]
    {
        let data = [];
        for(let item of objects) {
            data.push(_.extend(new MediaItem(), item));
        }
        return data;
    }

    showDropZone() {
        if(!this.data.dropZone) {
            this.data.dropZone = true;
        }
    }

    showDropZoneActive() {
        if(!this.data.dropZoneActive) {
            this.data.dropZoneActive = true;
        }
    }

    hideDropZone() {
        if(this.data.dropZone) {
            this.data.dropZone = false;
        }
    }

    hideDropZoneActive() {
        if(this.data.dropZoneActive) {
            this.data.dropZoneActive = false;
        }
    }

    public open(item: MediaItem)
    {
        let url = this.router.generate(this.data.updateRoute, {
            id: item.id
        });
        this.view.open(url, 'edit-view');
    }
}
