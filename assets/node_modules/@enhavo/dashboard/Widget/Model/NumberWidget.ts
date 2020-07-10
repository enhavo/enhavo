import ApplicationInterface from "@enhavo/app/ApplicationInterface";

export default class NumberWidget
{
    protected application: ApplicationInterface;

    component: string;
    label: string;
    value: string;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }
}