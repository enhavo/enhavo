import CreateEvent from '@enhavo/app/ViewStack/Event/CreateEvent';
import ClearEvent from '@enhavo/app/ViewStack/Event/ClearEvent';
import AbstractMenu from "@enhavo/app/Menu/Model/AbstractMenu";

export default class MenuItem extends AbstractMenu
{
    public url: string;

    open(): void {
        this.application.getEventDispatcher().dispatch(new ClearEvent())
            .then(() => {
                this.application.getEventDispatcher()
                    .dispatch(new CreateEvent({
                        label: this.label,
                        component: 'iframe-view',
                        url: this.url
                    }))
                    .then(() => {
                        this.getManager().clearSelections();
                        this.select();
                    });
            })
            .catch(() => {})
        ;
    }
}