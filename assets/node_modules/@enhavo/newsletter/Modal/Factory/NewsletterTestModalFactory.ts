import AbstractFactory from "@enhavo/app/Modal/Factory/AbstractFactory";
import NewsletterTestModal from "@enhavo/newsletter/Modal/Model/NewsletterTestModal";

export default class NewsletterTestModalFactory extends AbstractFactory
{
    createNew(): NewsletterTestModal {
        return new NewsletterTestModal(this.application);
    }
}