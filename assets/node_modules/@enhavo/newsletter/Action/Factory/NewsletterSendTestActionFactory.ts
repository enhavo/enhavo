import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import NewsletterSendTestAction from "@enhavo/newsletter/Action/Model/NewsletterSendTestAction";

export default class NewsletterSendTestActionFactory extends AbstractFactory
{
    createNew(): NewsletterSendTestAction {
        return new NewsletterSendTestAction(this.application);
    }
}