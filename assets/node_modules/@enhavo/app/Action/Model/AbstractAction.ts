import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import ActionInterface from "@enhavo/app/Action/ActionInterface";

export default abstract class AbstractAction implements ActionInterface
{
    protected application: ApplicationInterface;
    component: string;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }

    abstract execute(): void;
}