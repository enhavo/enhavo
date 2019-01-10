declare module "app/Templating"
{
    const templating: {
        render(templateSelector:string, parameters:Object): string;
        replace(text:string, parameters:Object): string;
    };

    export = templating;
}