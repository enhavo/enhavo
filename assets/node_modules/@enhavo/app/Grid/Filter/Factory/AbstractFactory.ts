import ApplicationInterface from "@enhavo/app/ApplicationInterface";

export default abstract class AbstractFactory
{
    protected application: ApplicationInterface;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }
}