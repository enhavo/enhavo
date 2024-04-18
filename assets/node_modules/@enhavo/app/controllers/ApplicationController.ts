import {AbstractController} from "./AbstractController";

export default class extends AbstractController
{
    static values = {
        application: String,
        component: String,
    }

    declare public applicationValue: string;
    declare public componentValue: string;

    connect() {
        this.init().then(() => {});
    }

    async init()
    {
        (await this.get(this.applicationValue)).init();
        (await this.get('@enhavo/app/vue/VueApp')).init('app', await this.get(this.componentValue));
    }
}
