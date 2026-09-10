import {AbstractController} from "./AbstractController";
import {VueFactory} from "@enhavo/framework/vue/VueFactory";
import {VueRouterFactory} from "@enhavo/framework/vue/VueRouterFactory";

export default class extends AbstractController
{
    static values = {
        props: Object,
        component: String,
    }

    declare public propsValue: object;
    declare public componentValue: string;

    connect() {
        this.init().then(() => {});
    }

    async init()
    {
        const vueFactory: VueFactory = await this.application.container.get('@enhavo/framework/vue/VueFactory');
        const vueRouterFactory: VueRouterFactory = await this.application.container.get('@enhavo/framework/vue/VueRouterFactory');
        const component = await vueFactory.getComponent(this.componentValue);
        const app = await vueFactory.createApp(component, this.propsValue);
        app.use(vueRouterFactory.createRouter());
        app.mount(this.element);
    }
}
