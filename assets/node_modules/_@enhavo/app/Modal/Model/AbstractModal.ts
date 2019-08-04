import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import ModalInterface from "@enhavo/app/Modal/ModalInterface";

export default abstract class AbstractModal implements ModalInterface
{
    protected application: ApplicationInterface;
    component: string;
    data: any;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }

    open(data: any): void
    {
        this.data = data;
    }

    close() {
        this.application.getModalManager().pop();
    }
}