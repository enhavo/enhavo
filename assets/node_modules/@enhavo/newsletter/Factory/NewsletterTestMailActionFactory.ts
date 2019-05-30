import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import ActionInterface from "@enhavo/app/Action/ActionInterface";
import NewsletterTestMailAction from "@enhavo/newsletter/Model/NewsletterTestMailAction";

export default class SaveActionFactory extends AbstractFactory
{
    createNew(): ActionInterface {
        return new NewsletterTestMailAction(this.application);
    }
}