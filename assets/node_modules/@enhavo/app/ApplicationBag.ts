import ApplicationInterface from "@enhavo/app/ApplicationInterface";

export class ApplicationBag
{
    private application: ApplicationInterface;

    public setApplication(application: ApplicationInterface)
    {
        this.application = application;
    }

    public getApplication(): ApplicationInterface
    {
        return this.application;
    }
}

let bag = new ApplicationBag();
export default bag;