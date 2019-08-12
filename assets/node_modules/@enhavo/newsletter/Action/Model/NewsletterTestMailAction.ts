import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class NewsletterTestMailAction extends AbstractAction
{
    execute(): void
    {
        this.application.getModalManager().push('newsletter-test-modal');
    }
}