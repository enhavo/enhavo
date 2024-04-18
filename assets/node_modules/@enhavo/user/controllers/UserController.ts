import { AbstractController } from "@enhavo/app/controllers/AbstractController"
import { VueFactory } from "@enhavo/app/vue/VueFactory";
import { VueRouterFactory } from "@enhavo/app/vue/VueRouterFactory";
import UserApp from "../components/UserApp.vue";

export default class extends AbstractController
{
    static values = {
        application: String,
        component: String,
    }

    connect() {
        this.init().then(() => {});
    }

    async init() {
        const vueFactory = await this.get("@enhavo/app/vue/VueFactory") as VueFactory;
        const vueRouterFactory = await this.application.container.get("@enhavo/app/vue/VueRouterFactory") as VueRouterFactory;
        const app = vueFactory.createApp(UserApp);
        app.use(vueRouterFactory.createRouter());
        app.mount(this.element);
    }
}
