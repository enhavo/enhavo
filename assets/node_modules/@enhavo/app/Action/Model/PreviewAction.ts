import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import CreateEvent from "@enhavo/app/ViewStack/Event/CreateEvent";
import ViewInterface from "@enhavo/app/ViewStack/ViewInterface";
import View from "@enhavo/app/View/View";
import * as $ from 'jquery';
import DataEvent from "@enhavo/app/ViewStack/Event/DataEvent";
import ExistsEvent from "@enhavo/app/ViewStack/Event/ExistsEvent";
import LoadedEvent from "@enhavo/app/ViewStack/Event/LoadedEvent";
import ExistsData from "@enhavo/app/ViewStack/ExistsData";
import SaveDataEvent from "@enhavo/app/ViewStack/Event/SaveDataEvent";
import LoadDataEvent from "@enhavo/app/ViewStack/Event/LoadDataEvent";

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

        let key = this.getPreviewKey();
        this.application.getEventDispatcher().dispatch(new LoadDataEvent(key))
            .then((data: PreviewData) => {
                if(data.id) {
                    this.previewView = data.id;
                }
            });
    }

    execute(): void
    {
        if(this.previewView == null) {
            this.openView();
        } else {
            this.existsPreviewView((exists: boolean) => {
                if(exists) {
                    this.sendData();
                } else {
                    this.openView()
                }
            });
        }
    }

    private sendData()
    {
        let data = this.getData();
        this.application.getEventDispatcher().dispatch(new DataEvent(this.previewView, data));
    }

    private existsPreviewView(callback: (exists: boolean) => void)
    {
        this.application.getEventDispatcher().dispatch(new ExistsEvent(this.previewView)).then((data: ExistsData) => {
            callback(data.exists);
        });
    }

    private openView()
    {
        this.application.getEventDispatcher().dispatch(new CreateEvent({
            label: this.application.getTranslator().trans('enhavo_app.preview'),
            component: 'iframe-view',
            url: this.url
        }, this.getView().getId()))
        .then((view: ViewInterface) => {
            this.setPreviewView(view.id);
        })
        .catch(() => {});
    }

    private setPreviewView(id: number)
    {
        this.previewView = id;
        let key = this.getPreviewKey();
        this.application.getEventDispatcher().dispatch(new SaveDataEvent(key, {
            id: id
        }));
    }

    private getPreviewKey()
    {
        return 'preview-view-' + this.application.getView().getId();
    }

    private getView(): View
    {
        return this.application.getView();
    }

    private getData()
    {
        return $('form').serializeArray();
    }
}

interface PreviewData {
    id: number;
}