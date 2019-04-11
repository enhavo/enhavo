import Router from "@enhavo/core/Router";
import MediaData from "@enhavo/media/MediaLibrary/MediaData";
import MediaItem from "@enhavo/media/MediaLibrary/MediaItem";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import axios from 'axios';
import * as _ from "lodash";
import View from "@enhavo/app/View/View";

export default class MediaLibrary
{
    private data: MediaData;
    private router: Router;
    private eventDispatcher: EventDispatcher;
    private view: View;

    constructor(data: MediaData, router: Router, eventDispatcher: EventDispatcher, view: View)
    {
        this.data = data;
        this.data = data;
        this.eventDispatcher = eventDispatcher;
        this.view = view;

        this.refresh();
    }

    setProgress(value: number)
    {
        this.data.progress = value;
    }

    refresh()
    {
        this.data.loading = true;
        let url = this.router.generate('enhavo_media_library_list', {
            page: this.data.page
        });

        axios
            .get(url, {params: []})
            .then(response => {
                this.data.items = this.createRowData(response.data.resources);
                this.data.count = parseInt(response.data.pages.count);
                this.data.page = parseInt(response.data.pages.page);
                this.data.loading = false;
            })
            .catch(error => {
                this.view.alert('error');
            })
            .then(() => {
                this.data.loading = false;
            })
    }

    private createRowData(objects: object[]): MediaItem[]
    {
        let data = [];
        for(let item of objects) {
            data.push(_.extend(new MediaItem(), item));
        }
        return data;
    }
}