import CreateEvent from '@enhavo/app/ViewStack/Event/CreateEvent';
import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import Grid from "@enhavo/app/Grid/Grid";
import {IndexApplication} from "@enhavo/app/Index/IndexApplication";
import ViewInterface from "@enhavo/app/ViewStack/ViewInterface";
import View from "@enhavo/app/ViewStack/View";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";

export default class CreateAction extends AbstractAction
{
    label: string;
    url: string;

    execute(): void
    {
        if(this.getGrid().getEditView() != null) {
            this.application.getEventDispatcher().dispatch(new CloseEvent(this.getGrid().getEditView()))
                .then(() => {
                    this.openView();
                })
                .catch(() => {})
            ;
        } else {
            this.openView();
        }
    }

    private openView()
    {
        this.application.getEventDispatcher().dispatch(new CreateEvent({
            label: 'edit',
            component: 'iframe-view',
            url: this.url
        }, this.getView().getId())).then((view: ViewInterface) => {
            this.getGrid().setEditView(view.id);
        }).catch(() => {});
    }

    private getGrid(): Grid
    {
        return (<IndexApplication>this.application).getGrid();
    }

    private getView(): View
    {
        return this.application.getView();
    }
}