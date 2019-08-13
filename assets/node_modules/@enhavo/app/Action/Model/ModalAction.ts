import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class ModalAction extends AbstractAction
{
    public modal: string;
    public options: string;

    execute(): void
    {
        this.application.getModalManager().push(this.modal, this.options);
    }
}