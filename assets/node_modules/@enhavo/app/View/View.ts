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

export default class View
{
    private readonly id: number|null;
    private data: ViewData;
    private eventDispatcher: EventDispatcher;

    constructor(data: ViewData, eventDispatcher: EventDispatcher)
    {
        this.eventDispatcher = eventDispatcher;
        this.id = this.getIdFromUrl();
        _.extend(data, new ViewData);
        this.data = data;
        this.data.id = this.id;

        window.addEventListener('click', () => {
            this.eventDispatcher.dispatch(new ClickEvent(this.id));
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
        return this.id;
    }

    isRoot()
    {
        return this.id == 0;
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

    private openView(label: string, url: string, key: string = null)
    {
        this.eventDispatcher.dispatch(new CreateEvent(        {
            label: label,
            component: 'iframe-view',
            url: url
        }, this.getId())).then((view: ViewInterface) => {
            if(key) {
                this.saveViewKey(key, view.id);
            }
            this.eventDispatcher.dispatch(new SaveStateEvent())
        }).catch(() => {});
    }

    private saveViewKey(key: string, id: number)
    {
        this.eventDispatcher.dispatch(new SaveDataEvent(key, id))
    }
}