import CreateEvent from '@enhavo/app/ViewStack/Event/CreateEvent';
import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import {IndexApplication} from "@enhavo/app/Index/IndexApplication";
import ViewInterface from "@enhavo/app/ViewStack/ViewInterface";
import View from "@enhavo/app/View/View";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";
import Editable from "@enhavo/app/Action/Editable";

export default class CreateAction extends AbstractAction
{
    label: string;
    url: string;

    execute(): void
    {
        if(this.getEditable().getEditView() != null) {
            this.application.getEventDispatcher().dispatch(new CloseEvent(this.getEditable().getEditView()))
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
            label: this.label,
            component: 'iframe-view',
            url: this.url
        }, this.getView().getId())).then((view: ViewInterface) => {
            this.getEditable().setEditView(view.id);
        }).catch(() => {});
    }

    private getEditable(): Editable
    {
        return (<IndexApplication>this.application).getEditable();
    }

    private getView(): View
    {
        return this.application.getView();
    }
}