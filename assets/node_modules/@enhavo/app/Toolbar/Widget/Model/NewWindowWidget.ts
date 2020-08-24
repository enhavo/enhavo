import AbstractWidget from "@enhavo/app/Toolbar/Widget/Model/AbstractWidget";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import MenuManager from "@enhavo/app/Menu/MenuManager";

export default class NewWindowWidget extends AbstractWidget
{
    public icon: string;
    public label: string;
    public url: string;

    public open()
    {
        window.open(window.location.href, '_blank');
    }
}