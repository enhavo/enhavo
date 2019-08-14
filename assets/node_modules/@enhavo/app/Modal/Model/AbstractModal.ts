import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import ModalInterface from "@enhavo/app/Modal/ModalInterface";

export default abstract class AbstractModal implements ModalInterface
{
    protected application: ApplicationInterface;
    component: string;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }

    init() {}

    close() {
        this.application.getModalManager().pop();
    }
}