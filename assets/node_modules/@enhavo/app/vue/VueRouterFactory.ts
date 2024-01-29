import * as VueRouter from "vue-router";
import { VueFactory } from "@enhavo/app/vue/VueFactory";

export class VueRouterFactory
{
    private router: VueRouter.Router;

    constructor(
        private vueFactory: VueFactory,
        private routes: any,
    ) {}

    getRouter(): VueRouter.Router
    {
        return this.router;
    }

    createRouter()
    {
        const routes = [];

        for (let routeName in this.routes) {
            routes.push({
                path: this.routes[routeName]["path"],
                name: this.routes[routeName]["name"],
                meta: this.routes[routeName]["meta"],
                component: this.vueFactory.getComponent(
                    this.routes[routeName]["component"],
                ),
            });
        }

        this.router = VueRouter.createRouter({
            history: VueRouter.createWebHistory(),
            routes,
        });

        return this.router;
    }
}
