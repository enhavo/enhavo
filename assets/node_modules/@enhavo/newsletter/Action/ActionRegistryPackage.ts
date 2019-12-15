import RegistryPackage from "@enhavo/core/RegistryPackage";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import NewsletterSendActionFactory from "@enhavo/newsletter/Action/Factory/NewsletterSendActionFactory";
import NewsletterSendTestActionFactory from "@enhavo/newsletter/Action/Factory/NewsletterSendTestActionFactory";

export default class ActionRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface) {
        super();
        this.register('newsletter-send', () => import('@enhavo/app/Action/Components/ActionComponent.vue'), new NewsletterSendActionFactory(application));
        this.register('newsletter-send-test', () => import('@enhavo/app/Action/Components/ActionComponent.vue'), new NewsletterSendTestActionFactory(application));
    }
}