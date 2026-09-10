
export class RouteContainer
{
    routes: RouteEntry[];

    constructor(routes: RouteEntry[])
    {
        this.routes = routes;
    }

    get(key: string): RouteEntry
    {
        for (let route of this.routes) {
            if (route.key === key) {
                return route;
            }
        }
        return null;
    }

    getRoute(key: string): string
    {
        return this.get(key)?.route;
    }

    getParameters(key: string): object
    {
        return this.get(key)?.parameters;
    }

    has(key: string): boolean
    {
        for (let route of this.routes) {
            if (route.key === key) {
                return true;
            }
        }
        return false;
    }
}


export class RouteEntry
{
    key: string;
    route: string;
    parameters: object;
}
