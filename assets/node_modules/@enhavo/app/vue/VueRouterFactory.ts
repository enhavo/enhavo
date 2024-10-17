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

        this.createRoutes(this.routes, routes);

        this.router = VueRouter.createRouter({
            history: VueRouter.createWebHistory(),
            routes,
        });

        return this.router;
    }

    private createRoutes(routesConfig, routes)
    {
        for (let routeName in routesConfig) {

            let route = {
                path: routesConfig[routeName]["path"],
                name: routesConfig[routeName]["name"],
                meta: routesConfig[routeName]["meta"],
                children: [],
                component: this.vueFactory.getComponent(
                    routesConfig[routeName]["component"],
                ),
            }

            if (typeof routesConfig[routeName]["children"] === 'object') {
                this.createRoutes(routesConfig[routeName]["children"], route.children);
            }

            routes.push(route);
        }
    }
}
