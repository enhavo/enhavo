import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import * as $ from 'jquery';
import DataEvent from "@enhavo/app/ViewStack/Event/DataEvent";
import ExistsEvent from "@enhavo/app/ViewStack/Event/ExistsEvent";
import LoadedEvent from "@enhavo/app/ViewStack/Event/LoadedEvent";
import ExistsData from "@enhavo/app/ViewStack/ExistsData";
import LoadDataEvent from "@enhavo/app/ViewStack/Event/LoadDataEvent";
import DataStorageEntry from "@enhavo/app/ViewStack/DataStorageEntry";

export default class PreviewAction extends AbstractAction
{
    public url: string;

    loadListener()
    {
        this.application.getView().loadValue('preview-view', (id) => {
            let viewId = id ? parseInt(id) : null;
            if(viewId) {
                this.sendData(viewId);
            }
        });

        this.application.getEventDispatcher().on('loaded', (event: LoadedEvent) => {
            this.application.getView().loadValue('preview-view', (id) => {
                let viewId = id ? parseInt(id) : null;
                if(event.id == viewId) {
                    this.sendData(viewId);
                }
            });
        });
    }

    execute(): void
    {
        let label = this.application.getTranslator().trans('enhavo_app.preview');
        this.open(label, this.url, 'preview-view');
    }

    private sendData(id :number)
    {
        let data = $('form').serializeArray();
        this.application.getEventDispatcher().dispatch(new DataEvent(id, data));
    }

    private open(label: string, url: string, key: string = null)
    {
        this.application.getEventDispatcher().dispatch(new LoadDataEvent(key)).then((data: DataStorageEntry) => {
            let viewId: number = null;
            if(data) {
                viewId = data.value;
            }
            if(viewId != null) {
                this.application.getEventDispatcher().dispatch(new ExistsEvent(viewId)).then((data: ExistsData) => {
                    if(data.exists) {
                        this.sendData(viewId);
                    } else {
                        this.application.getView().openView(label, url, key);
                    }
                });
            } else {
                this.application.getView().openView(label, url, key);
            }
        });
    }
}