import AbstractWidget from "@enhavo/app/Toolbar/Widget/Model/AbstractWidget";
import ClearEvent from "@enhavo/app/ViewStack/Event/ClearEvent";
import CreateEvent from "@enhavo/app/ViewStack/Event/CreateEvent";
import MenuAwareApplication from "@enhavo/app/Menu/MenuAwareApplication";

export default class IconWidget extends AbstractWidget
{
    public icon: string;
    public label: string;
    public url: string;
    public target: string;

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