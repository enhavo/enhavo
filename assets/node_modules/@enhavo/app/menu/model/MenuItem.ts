import CreateEvent from '@enhavo/app/ViewStack/Event/CreateEvent';
import ClearEvent from '@enhavo/app/ViewStack/Event/ClearEvent';
import SaveStateEvent from '@enhavo/app/ViewStack/Event/SaveStateEvent';
import AbstractMenu from "@enhavo/app/Menu/Model/AbstractMenu";
import MenuList from "@enhavo/app/Menu/Model/MenuList";

export default class MenuItem extends AbstractMenu
{
    public url: string;
    public mainUrl: string;
    public clickable: boolean = true;

    open(): void {
        this.eventDispatcher.dispatch(new ClearEvent())
            .then(() => {
                this.eventDispatcher
                    .dispatch(new CreateEvent({
                        component: 'iframe-view',
                        url: this.url
                    }))
                    .then(() => {
                        this.getManager().clearSelections();
                        this.getManager().setActive(this.key);
                        this.select();
                        this.eventDispatcher.dispatch(new SaveStateEvent());
                        if(!this.getManager().isOpen()) {
                            if(this.parent()) {
                                (<MenuList>this.parent()).close();
                            }
                        }
                    });
            })
            .catch(() => {})
        ;
    }
}