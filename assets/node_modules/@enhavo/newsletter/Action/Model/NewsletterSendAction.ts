import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class NewsletterSendAction extends AbstractAction
{
    execute(): void
    {
        this.application.getView().loading();
    }
}