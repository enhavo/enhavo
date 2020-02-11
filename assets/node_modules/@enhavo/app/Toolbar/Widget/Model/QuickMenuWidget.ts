import AbstractWidget from "@enhavo/app/Toolbar/Widget/Model/AbstractWidget";
import CreateEvent from '@enhavo/app/ViewStack/Event/CreateEvent';
import ClearEvent from '@enhavo/app/ViewStack/Event/ClearEvent';
import MenuAwareApplication from "@enhavo/app/Menu/MenuAwareApplication";

export default class QuickMenuWidget extends AbstractWidget
{
    public menu: Menu[];
    public icon: string;

    public getApplication()
    {
        return this.application;
    }

    private openView(url: string, label: string)
    {
        this.getApplication().getEventDispatcher().dispatch(new ClearEvent())
            .then(() => {
                this.getApplication().getEventDispatcher()
                    .dispatch(new CreateEvent({
                        label: label,
                        component: 'iframe-view',
                        url: url
                    }))
                    .then(() => {
                        (<MenuAwareApplication>this.getApplication()).getMenuManager().clearSelections();
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