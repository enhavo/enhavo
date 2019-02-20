import ActionInterface from "@enhavo/app/Action/ActionInterface";
import dispatcher from '../../ViewStack/dispatcher';
import CreateEvent from '@enhavo/app/ViewStack/Event/CreateEvent';

export default class CreateAction implements ActionInterface
{
    component: string;
    label: string;
    url: string;

    execute(): void
    {
        dispatcher.dispatch(new CreateEvent({
            label: 'edit',
            component: 'iframe-view',
            url: this.url
        }));
    }
}