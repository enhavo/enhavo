import AbstractWidget from "@enhavo/app/toolbar/widget/model/AbstractWidget";
import CreateEvent from '@enhavo/app/view-stack/Event/CreateEvent';
import ClearEvent from '@enhavo/app/view-stack/Event/ClearEvent';
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import MenuManager from "@enhavo/app/menu/MenuManager";

export default class QuickMenuWidget extends AbstractWidget
{
    public menu: Menu[];
    public icon: string;

    private readonly eventDispatcher: EventDispatcher;
    private readonly menuManager: MenuManager;

    constructor(eventDispatcher: EventDispatcher, menuManager: MenuManager) {
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

    public open(menu: Menu)
    {
        if(menu.target == '_view') {
            this.openView(menu.url, menu.label);
        } else if(menu.target == '_self') {
            window.location.href = menu.url;
        } else if(menu.target == '_blank') {
            window.open(menu.url, '_blank');
        }
    }
}

export class Menu
{
    public target: string;
    public url: string;
    public label: string;
}