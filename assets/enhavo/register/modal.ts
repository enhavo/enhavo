import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import NewsletterTestModalFactory from "@enhavo/newsletter/Modal/Factory/NewsletterTestModalFactory";

export default function register(application: ApplicationInterface) {
    application.getModalRegistry()
        .register('newsletter-test-modal', () => import('@enhavo/newsletter/Modal/Components/NewsletterTestModalComponent.vue'), new NewsletterTestModalFactory(application));

}