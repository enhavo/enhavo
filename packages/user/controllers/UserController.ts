import { AbstractController } from "@enhavo/framework/controllers/AbstractController"
import { VueFactory } from "@enhavo/framework/vue/VueFactory";
import { VueRouterFactory } from "@enhavo/framework/vue/VueRouterFactory";

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
        const vueFactory = await this.get("@enhavo/framework/vue/VueFactory") as VueFactory;
        const vueRouterFactory = await this.application.container.get("@enhavo/framework/vue/VueRouterFactory") as VueRouterFactory;
        const component = await vueFactory.getComponent('user-app');
        const app = await vueFactory.createApp(component);
        app.use(vueRouterFactory.createRouter());
        app.mount(this.element);
    }
}
