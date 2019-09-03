import CreateEvent from '@enhavo/app/ViewStack/Event/CreateEvent';
import ClearEvent from '@enhavo/app/ViewStack/Event/ClearEvent';
import SaveStateEvent from '@enhavo/app/ViewStack/Event/SaveStateEvent';
import AbstractMenu from "@enhavo/app/Menu/Model/AbstractMenu";

export default class MenuItem extends AbstractMenu
{
    public url: string;
    public mainUrl: string;
    public clickable: boolean = true;

    open(): void {
        this.application.getEventDispatcher().dispatch(new ClearEvent())
            .then(() => {
                this.application.getEventDispatcher()
                    .dispatch(new CreateEvent({
                        component: 'iframe-view',
                        url: this.url
                    }))
                    .then(() => {
                        this.getManager().clearSelections();
                        this.getManager().setActive(this.key);
                        this.select();
                        this.application.getEventDispatcher().dispatch(new SaveStateEvent());
                    });
            })
            .catch(() => {})
        ;
    }
}