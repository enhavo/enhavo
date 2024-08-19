import CreateEvent from '@enhavo/app/view-stack/event/CreateEvent';
import ClearEvent from '@enhavo/app/view-stack/event/ClearEvent';
import SaveStateEvent from '@enhavo/app/view-stack/event/SaveStateEvent';
import {AbstractMenuItem} from "@enhavo/app/menu/model/AbstractMenuItem";
import EventDispatcher from "../../view-stack/EventDispatcher";

export class BaseMenuItem extends AbstractMenuItem
{
    public url: string;
    public mainUrl: string;
    public clickable: boolean = true;

    constructor(
        private eventDispatcher: EventDispatcher,
    ) {
        super();
    }

    open(): void {
        this.eventDispatcher.dispatch(new ClearEvent())
            .then(() => {
                this.eventDispatcher
                    .dispatch(new CreateEvent({
                        component: 'iframe-view',
                        url: this.url
                    }))
                    .then(() => {
                        this.select();
                        this.eventDispatcher.dispatch(new SaveStateEvent());
                    });
            })
            .catch(() => {})
        ;
    }

    isActive(): boolean {
        return false;
    }
}
