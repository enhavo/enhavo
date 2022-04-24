import { Controller } from "@hotwired/stimulus"

export default class extends Controller
{
    static values = {
        application: String,
        component: String,
    }

    connect() {
        this.init().then(() => {});
    }

    async init() {
        (await this.application.container.get(this.applicationValue)).init();
        (await this.application.container.get('@enhavo/app/vue/VueApp')).init('app', await this.application.container.get(this.componentValue));
    }
}
