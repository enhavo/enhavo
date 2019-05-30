import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import NewsletterSendActionFactory from "@enhavo/newsletter/Factory/NewsletterSendActionFactory";
import NewsletterTestMailActionFactory from "@enhavo/newsletter/Factory/NewsletterTestMailActionFactory";

export default function register(application: ApplicationInterface) {
    application.getActionRegistry()
        .register('newsletter-send',() => import('@enhavo/app/Action/Components/ActionComponent.vue'), new NewsletterSendActionFactory(application))
        .register('newsletter-test-mail',() => import('@enhavo/app/Action/Components/ActionComponent.vue'), new NewsletterTestMailActionFactory(application))

}