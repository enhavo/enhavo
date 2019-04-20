import Router from "@enhavo/core/Router";
import MediaData from "@enhavo/media/MediaLibrary/MediaData";
import MediaItem from "@enhavo/media/MediaLibrary/MediaItem";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import axios from 'axios';
import * as _ from "lodash";
import View from "@enhavo/app/View/View";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";
import CreateEvent from "@enhavo/app/ViewStack/Event/CreateEvent";
import ViewInterface from "@enhavo/app/ViewStack/ViewInterface";
import Translator from "@enhavo/core/Translator";
import RemovedEvent from "@enhavo/app/ViewStack/Event/RemovedEvent";
import UpdatedEvent from "@enhavo/app/ViewStack/Event/UpdatedEvent";

export default class MediaLibrary
{
    private data: MediaData;
    private router: Router;
    private eventDispatcher: EventDispatcher;
    private view: View;
    private translator: Translator;

    constructor(data: MediaData, router: Router, eventDispatcher: EventDispatcher, view: View, translator: Translator)
    {
        _.extend(data, new MediaData());
        this.data = data;
        this.eventDispatcher = eventDispatcher;
        this.view = view;
        this.router = router;
        this.translator = translator;

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

    hideDropZone() {
        if(this.data.dropZone) {
            this.data.dropZone = false;
        }
    }

    public open(item: MediaItem)
    {
        if(this.data.editView != null) {
            this.eventDispatcher.dispatch(new CloseEvent(this.data.editView))
                .then(() => {
                    this.openView(item);
                })
                .catch(() => {})
            ;
        } else {
            this.openView(item);
        }
    }

    protected openView(item: MediaItem)
    {
        let url = this.router.generate(this.data.updateRoute, {
            id: item.id
        });

        this.eventDispatcher.dispatch(new CreateEvent({
            label: this.translator.trans('enhavo_app.edit'),
            component: 'iframe-view',
            url: url
        }, this.view.getId())).then((view: ViewInterface) => {
            this.data.editView = view.id;
        }).catch(() => {});
    }

}