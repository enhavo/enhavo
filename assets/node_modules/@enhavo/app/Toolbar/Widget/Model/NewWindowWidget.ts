import AbstractWidget from "@enhavo/app/Toolbar/Widget/Model/AbstractWidget";

export default class NewWindowWidget extends AbstractWidget
{
    public icon: string;
    public label: string;
    public url: string;

    public getApplication()
    {
        return this.application;
    }

    public open()
    {
        window.open(window.location.href, '_blank');
    }
}