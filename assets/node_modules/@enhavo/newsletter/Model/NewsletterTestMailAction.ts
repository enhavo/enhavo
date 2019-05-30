import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";

export default class NewsletterTestMailAction extends AbstractAction
{
    execute(): void
    {
        console.log('test mail');
    }
}