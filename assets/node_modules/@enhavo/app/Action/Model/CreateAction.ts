import CreateEvent from '@enhavo/app/ViewStack/Event/CreateEvent';
import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class CreateAction extends AbstractAction
{
    label: string;
    url: string;

    execute(): void
    {
        this.application.getEventDispatcher().dispatch(new CreateEvent({
            label: 'edit',
            component: 'iframe-view',
            url: this.url
        }));
    }
}