import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import CreateEvent from "@enhavo/app/ViewStack/Event/CreateEvent";
import ViewInterface from "@enhavo/app/ViewStack/ViewInterface";
import View from "@enhavo/app/View/View";
import * as $ from 'jquery';
import DataEvent from "@enhavo/app/ViewStack/Event/DataEvent";
import ExistsEvent from "@enhavo/app/ViewStack/Event/ExistsEvent";
import LoadedEvent from "@enhavo/app/ViewStack/Event/LoadedEvent";
import ExistsData from "@enhavo/app/ViewStack/ExistsData";

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
            label: 'preview',
            component: 'iframe-view',
            url: this.url
        }, this.getView().getId()))
        .then((view: ViewInterface) => {
            this.previewView = view.id;
        })
        .catch(() => {});
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