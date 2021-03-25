import AbstractWidget from "@enhavo/app/toolbar/widget/model/AbstractWidget";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import MenuManager from "@enhavo/app/menu/MenuManager";

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