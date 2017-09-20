export interface Templating {
    render(templateSelector:string, parameters:Object): string;
    replace(text:string, parameters:Object): string;
}