
export class Router
{
    private routes: RoutesMap

    public constructor(data: RoutesMap)
    {
        this.routes = data;
    }

    public setRoutes(data: RoutesMap)
    {
        this.routes = data;
    }

    getRoute(name: string): Route
    {
        if (!(name in this.routes)) {
            throw new Error('The route "' + name + '" does not exist.');
        }

        return this.routes[name];
    }

    hasRoute(name: string): boolean
    {
        return name in this.routes;
    }

    generate(name: string, parameters?: RouteParams, absolute?: boolean): string
    {
        let route = this.getRoute(name);
        let variables = route.path.match(/{(!)?([\w\x80-\xFF]+)}/g);

        let unusedParameters: string[] = typeof parameters === 'object' ? Object.keys(parameters) : [];
        let usedParameters: string[] = [];
        let missingParameters: string[] = [];
        let defaultParameters: string[] = [];

        if (variables) {
            for (let variable of variables) {
                let name = variable.match(/{(!)?([\w\x80-\xFF]+)}/)[2];

                let parameterExists = false;
                if (typeof parameters === 'object') {
                    parameterExists = parameters.hasOwnProperty(name);
                }

                if (parameterExists) {
                    usedParameters.push(name);
                    unusedParameters.splice(unusedParameters.indexOf(name), 1);
                } else {
                    missingParameters.push(name);
                }
            }
        }

        for (let missingParameter of missingParameters) {
            if (route.defaults.hasOwnProperty(missingParameter)) {
                defaultParameters.push(missingParameter);
                missingParameters.splice(missingParameters.indexOf(missingParameter), 1);
            }
        }

        if (missingParameters.length > 0) {
            throw 'Missing parameters to generate url: "' + missingParameters.join(', ') + '"';
        }

        let url = route.path;
        for (let usedParameter of usedParameters) {
            url = url.replace('{' + usedParameter + '}', parameters[usedParameter]);
        }

        for (let defaultParameter of defaultParameters) {
            url = url.replace('{' + defaultParameter + '}', route.defaults[defaultParameter]);
        }

        if (url === '') {
            url = '/'
        }

        if (unusedParameters.length > 0) {
            let paramsObject = {};
            for (let unusedParameter of unusedParameters) {
                paramsObject[unusedParameter] = parameters[unusedParameter];
            }


            let prefix;
            let queryParams: string[] = [];
            let add = (key: string, value: any) => {
                // if value is a function then call it and assign it's return value as value
                value = (typeof value === 'function') ? value() : value;

                // change null to empty string
                value = (value === null) ? '' : value;

                queryParams.push(encodeURIComponent(key) + '=' + encodeURIComponent(value));
            };

            for (prefix in paramsObject) {
                this.buildQueryParams(prefix, paramsObject[prefix], add);
            }

            url = url + '?' + queryParams.join('&').replace(/%20/g, '+');
        }

        if (absolute) {
            let prefix = location.protocol + '//' + location.host;
            if (location.port) {
                prefix = prefix + ':' + location.port;
            }
            return prefix + url;
        }

        return url;
    }

    /**
     * Builds query string params added to a URL.
     * Port of jQuery's $.param() function, so credit is due there.
     *
     * @param {string} prefix
     * @param {Array|Object|string} params
     * @param {Function} add
     */
    buildQueryParams(prefix: string, params: any, add: (prefix:string, params:any) => void): void
    {
        let name;
        let rbracket = new RegExp(/\[\]$/);

        if (params instanceof Array) {
            params.forEach((val, i) => {
                if (rbracket.test(prefix)) {
                    add(prefix, val);
                } else {
                    this.buildQueryParams(prefix + '[' + (typeof val === 'object' ? i : '') + ']', val, add);
                }
            });
        } else if (typeof params === 'object') {
            for (name in params) {
                this.buildQueryParams(prefix + '[' + name + ']', params[name], add);
            }
        } else {
            add(prefix, params);
        }
    }
}

export interface RoutesMap
{
    [index:string]: Route;
}

export interface RouteParams
{
    [index:string]: any;
}

class Route
{
    public path: string;
    public defaults: RouteParams;
}
