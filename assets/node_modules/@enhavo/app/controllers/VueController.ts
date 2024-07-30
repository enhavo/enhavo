import {AbstractController} from "./AbstractController";
import {VueFactory} from "@enhavo/app/vue/VueFactory";
import {VueRouterFactory} from "@enhavo/app/vue/VueRouterFactory";

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
        const vueFactory: VueFactory = await this.application.container.get('@enhavo/app/vue/VueFactory');
        const vueRouterFactory: VueRouterFactory = await this.application.container.get('@enhavo/app/vue/VueRouterFactory');
        const component = vueFactory.getComponent(this.componentValue);
        const app = vueFactory.createApp(component, this.propsValue);
        app.use(vueRouterFactory.createRouter());
        app.mount(this.element);
    }
}
