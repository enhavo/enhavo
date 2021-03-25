import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import * as $ from 'jquery';
import DataEvent from "@enhavo/app/ViewStack/Event/DataEvent";
import ExistsEvent from "@enhavo/app/ViewStack/Event/ExistsEvent";
import LoadedEvent from "@enhavo/app/ViewStack/Event/LoadedEvent";
import ExistsData from "@enhavo/app/ViewStack/ExistsData";
import LoadDataEvent from "@enhavo/app/ViewStack/Event/LoadDataEvent";
import DataStorageEntry from "@enhavo/app/ViewStack/DataStorageEntry";
import View from "@enhavo/app/View/View";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";

export default class PreviewAction extends AbstractAction
{
    public url: string;

    private static listenerLoaded = false;

    private readonly view: View;
    private readonly eventDispatcher: EventDispatcher;

    constructor(view: View, eventDispatcher: EventDispatcher) {
        super();
        this.view = view;
        this.eventDispatcher = eventDispatcher;
    }

    loadListener()
    {
        if(PreviewAction.listenerLoaded) {
            return;
        }

        PreviewAction.listenerLoaded = true;

        this.view.loadValue('preview-view', (id) => {
            let viewId = id ? parseInt(id) : null;
            if(viewId) {
                this.sendData(viewId);
            }
        });

        this.eventDispatcher.on('loaded', (event: LoadedEvent) => {
            this.view.loadValue('preview-view', (id) => {
                let viewId = id ? parseInt(id) : null;
                if(event.id == viewId) {
                    this.sendData(viewId);
                }
            });
        });
    }

    execute(): void
    {
        this.open(this.url, 'preview-view');
    }

    private sendData(id :number)
    {
        let data = $('form').serializeArray();
        this.eventDispatcher.dispatch(new DataEvent(id, data));
    }

    private open(url: string, key: string = null, label: string = null)
    {
        this.eventDispatcher.dispatch(new LoadDataEvent(key)).then((data: DataStorageEntry) => {
            let viewId: number = null;
            if(data) {
                viewId = data.value;
            }
            if(viewId != null) {
                this.eventDispatcher.dispatch(new ExistsEvent(viewId)).then((data: ExistsData) => {
                    if(data.exists) {
                        this.sendData(viewId);
                    } else {
                        this.view.openView(url, key, label);
                    }
                });
            } else {
                this.view.openView(url, key, label);
            }
        });
    }
}
