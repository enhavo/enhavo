import {AbstractToolbarWidget} from "@enhavo/app/toolbar/model/AbstractToolbarWidget";
import {FrameEventDispatcher} from "@enhavo/app/frame/FrameEventDispatcher";
import MenuManager from "@enhavo/app/menu/MenuManager";

export class IconToolbarWidget extends AbstractToolbarWidget
{
    public icon: string;
    public label: string;
    public url: string;
    public target: string;

    private readonly eventDispatcher: FrameEventDispatcher;
    private readonly menuManager: MenuManager;

    constructor(eventDispatcher: FrameEventDispatcher, menuManager: MenuManager) {
        super();
        this.eventDispatcher = eventDispatcher;
        this.menuManager = menuManager;
    }

    private openView(url: string, label: string)
    {
        this.eventDispatcher.dispatch(new ClearEvent())
            .then(() => {
                this.eventDispatcher
                    .dispatch(new CreateEvent({
                        label: label,
                        component: 'iframe-view',
                        url: url
                    }))
                    .then(() => {
                        this.menuManager.clearSelections();
                    });
            })
            .catch(() => {})
        ;
    }

    public open()
    {
        if(this.target == '_view') {
            this.openView(this.url, this.label);
        } else if(this.target == '_self') {
            window.location.href = this.url;
        } else if(this.target == '_blank') {
            window.open(this.url, '_blank');
        }
    }
}