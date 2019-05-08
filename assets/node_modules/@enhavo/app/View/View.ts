import ViewData from "@enhavo/app/View/ViewData";
import Confirm from "@enhavo/app/View/Confirm";
import * as _ from 'lodash';
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import ClickEvent from "@enhavo/app/ViewStack/Event/ClickEvent";
import CreateEvent from "@enhavo/app/ViewStack/Event/CreateEvent";
import ExistsEvent from "@enhavo/app/ViewStack/Event/ExistsEvent";
import ExistsData from "@enhavo/app/ViewStack/ExistsData";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";
import ViewInterface from "@enhavo/app/ViewStack/ViewInterface";
import LoadDataEvent from "@enhavo/app/ViewStack/Event/LoadDataEvent";
import DataStorageEntry from "@enhavo/app/ViewStack/DataStorageEntry";
import SaveDataEvent from "@enhavo/app/ViewStack/Event/SaveDataEvent";
import SaveStateEvent from "@enhavo/app/ViewStack/Event/SaveStateEvent";
import RemoveEvent from "@enhavo/app/ViewStack/Event/RemoveEvent";
import LoadedEvent from "@enhavo/app/ViewStack/Event/LoadedEvent";
import LoadingEvent from "@enhavo/app/ViewStack/Event/LoadingEvent";
import {type} from "os";

export default class View
{
    private data: ViewData;
    private eventDispatcher: EventDispatcher;

    constructor(data: ViewData = null)
    {
        if(data === null) {
            data = new ViewData;
        }
        _.extend(data, new ViewData);
        this.data = data;

        if(this.data.id == null) {
            this.data.id = this.getIdFromUrl();
        }

        if(typeof this.data.id == 'string') {
            this.data.id = parseInt(this.data.id);
        }

        window.addEventListener('click', () => {
            this.eventDispatcher.dispatch(new ClickEvent(this.getId()));
        });
    }

    private getIdFromUrl(): number|null
    {
        let url = new URL(window.location.href);
        let id = parseInt(url.searchParams.get("view_id"));
        if(id > 0) {
            return id
        }
        return 0;
    }

    getId(): number|null
    {
        return this.data.id;
    }

    isRoot()
    {
        return this.data.id == 0;
    }

    confirm(confirm: Confirm)
    {
        if(confirm == null) {
            this.data.confirm = null;
        } else if(this.data.confirm == null) {
            confirm.setView(this);
            this.data.confirm = confirm;
        }
    }

    alert(message: string)
    {
        if(this.data.alert == null) {
            this.data.alert = message;
        }
    }

    loading()
    {
        this.data.loading = true;
    }

    loaded()
    {
        this.data.loading = false;
    }

    public open(label: string, url: string, key: string = null)
    {
        if(key) {
            this.eventDispatcher.dispatch(new LoadDataEvent(key)).then((data: DataStorageEntry) => {
                let viewId: number = null;
                if(data != null) {
                    viewId = data.value;
                }
                if(viewId != null) {
                    this.eventDispatcher.dispatch(new ExistsEvent(viewId)).then((data: ExistsData) => {
                        if(data.exists) {
                            this.eventDispatcher.dispatch(new CloseEvent(viewId))
                                .then(() => {
                                    this.openView(label, url, key);
                                })
                                .catch(() => {})
                            ;
                        } else {
                            this.openView(label, url, key);
                        }
                    });
                } else {
                    this.openView(label, url, key);
                }
            });
        } else {
            this.open(label, url)
        }
    }

    public openView(label: string, url: string, key: string = null)
    {
        this.eventDispatcher.dispatch(new CreateEvent(        {
            label: label,
            component: 'iframe-view',
            url: url
        }, this.getId())).then((view: ViewInterface) => {
            if(key) {
                this.storeValue(key, view.id);
            }
            this.eventDispatcher.dispatch(new SaveStateEvent())
        }).catch(() => {});
    }

    public storeValue(key: string, value: any, callback: () => void = () => {})
    {
        this.eventDispatcher.dispatch(new SaveDataEvent(key, value)).then(() => {
            callback();
        })
    }

    public loadValue(key: string, callback: (value: string) => void): void
    {
        this.eventDispatcher.dispatch(new LoadDataEvent(key)).then((data: DataStorageEntry) => {
            let value = null;
            if (data != null) {
                value = data.value;
            }
            callback(value);
        });
    }

    public addDefaultCloseListener(): void
    {
        this.eventDispatcher.on('close', (event: CloseEvent) => {
            if(this.getId() === event.id) {
                event.resolve();
                this.eventDispatcher.dispatch(new RemoveEvent(this.getId()));
            }
        });
    }

    public ready()
    {
        this.eventDispatcher.dispatch(new LoadedEvent(this.getId()));
    }

    public exit()
    {
        this.eventDispatcher.dispatch(new LoadingEvent(this.getId()));
    }

    public setEventDispatcher(dispatcher: EventDispatcher)
    {
        this.eventDispatcher = dispatcher;
    }
}