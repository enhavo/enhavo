import AbstractFactory from "@enhavo/app/Modal/Factory/AbstractFactory";
import NewsletterTestModal from "@enhavo/newsletter/Modal/Model/NewsletterTestModal";

export default class NewsletterTestModalFactory extends AbstractFactory
{
    createNew(): NewsletterTestModal {
        let modal = new NewsletterTestModal(this.application);
        modal.component = 'newsletter-test-modal';
        return modal;
    }
}