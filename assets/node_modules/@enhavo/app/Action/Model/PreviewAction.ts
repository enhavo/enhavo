import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import CreateEvent from "@enhavo/app/ViewStack/Event/CreateEvent";
import ViewInterface from "@enhavo/app/ViewStack/ViewInterface";
import * as $ from 'jquery';
import DataEvent from "@enhavo/app/ViewStack/Event/DataEvent";
import ExistsEvent from "@enhavo/app/ViewStack/Event/ExistsEvent";
import LoadedEvent from "@enhavo/app/ViewStack/Event/LoadedEvent";
import ExistsData from "@enhavo/app/ViewStack/ExistsData";
import SaveDataEvent from "@enhavo/app/ViewStack/Event/SaveDataEvent";
import LoadDataEvent from "@enhavo/app/ViewStack/Event/LoadDataEvent";
import DataStorageEntry from "@enhavo/app/ViewStack/DataStorageEntry";
import SaveStateEvent from "@enhavo/app/ViewStack/Event/SaveStateEvent";

export default class PreviewAction extends AbstractAction
{
    public url: string;
    public previewView: number;

    loadListener()
    {
        this.application.getEventDispatcher().on('loaded', (event: LoadedEvent) => {
            if(event.id == this.previewView) {
                this.sendData();
            }
        });
    }

    execute(): void
    {
        let label = this.application.getTranslator().trans('enhavo_app.preview');
        this.open(label, this.url, 'preview-view');
    }

    private sendData()
    {
        let data = $('form').serializeArray();
        this.application.getEventDispatcher().dispatch(new DataEvent(this.previewView, data));
    }

    private open(label: string, url: string, key: string = null)
    {
        this.application.getEventDispatcher().dispatch(new LoadDataEvent(key)).then((data: DataStorageEntry) => {
            let viewId: number = null;
            if(data) {
                viewId = data.value;
            }
            this.previewView = viewId;
            if(viewId != null) {
                this.application.getEventDispatcher().dispatch(new ExistsEvent(viewId)).then((data: ExistsData) => {
                    if(data.exists) {
                        this.sendData();
                    } else {
                        this.openView(label, url, key);
                    }
                });
            } else {
                this.openView(label, url, key);
            }
        });
    }

    private openView(label: string, url: string, key: string)
    {
        this.application.getEventDispatcher().dispatch(new CreateEvent({
            label: label,
            component: 'iframe-view',
            url: url
        }, this.application.getView().getId())).then((view: ViewInterface) => {
            this.saveViewKey(key, view.id);
            this.application.getEventDispatcher().dispatch(new SaveStateEvent())
        }).catch(() => {});
    }

    private saveViewKey(key: string, id: number)
    {
        this.previewView = id;
        this.application.getEventDispatcher().dispatch(new SaveDataEvent(key, id))
    }
}