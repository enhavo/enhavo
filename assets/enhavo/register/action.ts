import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import NewsletterSendActionFactory from "@enhavo/newsletter/Action/Factory/NewsletterSendActionFactory";

export default function register(application: ApplicationInterface) {
    application.getActionRegistry()
        .register('newsletter-send',() => import('@enhavo/app/Action/Components/ActionComponent.vue'), new NewsletterSendActionFactory(application))

}