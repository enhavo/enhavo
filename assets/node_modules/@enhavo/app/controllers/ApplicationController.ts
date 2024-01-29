import {AbstractController} from "./AbstractController";

export default class extends AbstractController
{
    static values = {
        application: String,
        component: String,
    }

    public applicationValue: string;
    public componentValue: string;

    connect() {
        this.init().then(() => {});
    }

    async init() {
        (await this.application.container.get(this.applicationValue)).init();
        (await this.application.container.get('@enhavo/app/vue/VueApp')).init('app', await this.application.container.get(this.componentValue));
    }
}
