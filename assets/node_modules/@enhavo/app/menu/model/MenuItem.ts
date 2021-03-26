import CreateEvent from '@enhavo/app/view-stack/Event/CreateEvent';
import ClearEvent from '@enhavo/app/view-stack/Event/ClearEvent';
import SaveStateEvent from '@enhavo/app/view-stack/Event/SaveStateEvent';
import AbstractMenu from "@enhavo/app/menu/model/AbstractMenu";
import MenuList from "@enhavo/app/menu/model/MenuList";

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