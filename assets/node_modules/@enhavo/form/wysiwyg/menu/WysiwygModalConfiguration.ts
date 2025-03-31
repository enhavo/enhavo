export class WysiwygModalConfiguration
{
    private promise: Promise<object>;
    private promiseResolve: (result: object) => void;
    private promiseReject: () => void;

    public component: string;
    public options: object;

    constructor(component: string, options: object) {
        this.component = component;
        this.options = options;

        this.promise = new Promise<object>((resolve, reject) => {
            this.promiseResolve = resolve;
            this.promiseReject = reject;
        });
    }

    public submit(result: object)
    {
        this.promiseResolve(result);
    }

    public cancel()
    {
        this.promiseReject();
    }

    public getPromise()
    {
        return this.promise;
    }
}
