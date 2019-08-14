import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class ModalAction extends AbstractAction
{
    public modal: any;

    execute(): void
    {
        this.application.getModalManager().push(this.modal);
    }
}