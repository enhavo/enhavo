import {AbstractToolbarWidget} from "@enhavo/app/toolbar/model/AbstractToolbarWidget";

export class NewWindowToolbarWidget extends AbstractToolbarWidget
{
    public icon: string;
    public label: string;
    public url: string;

    public open()
    {
        window.open(window.location.href, '_blank');
    }
}