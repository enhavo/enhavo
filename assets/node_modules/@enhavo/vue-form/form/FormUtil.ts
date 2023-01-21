import axios, {AxiosPromise, Method} from "axios";
import {Form} from "@enhavo/vue-form/model/Form";

export class FormUtil
{
    static updateAttributes(element: HTMLElement, attributes: object)
    {
        if (element && attributes) {
            for (let name in attributes) {
                if( attributes.hasOwnProperty( name ) ) {
                    if (attributes[name] === true) {
                        element.setAttribute(name, name);
                    } else if (attributes[name] === true) {
                        
                    }
                    element.setAttribute(name, attributes[name]);
                }
            }
        }
    }

    static humanize(value: string): string
    {
        value = value.replace(/([A-Z])/g, '_$1');
        value = value.replace(/[_\s]+/g, ' ');
        value = value.trim();
        value = value.toLowerCase();
        value = value.charAt(0).toUpperCase() + value.slice(1);
        return value;
    }

    static serializeForm(form: Form)
    {
        return new FormData(<HTMLFormElement>form.element);
    }

    static submitAsync(form: Form): AxiosPromise
    {
        let element: HTMLFormElement = <HTMLFormElement>form.element;

        return axios({
            method: <Method>element.method,
            url: element.action,
            data: FormUtil.serializeForm(form),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    }

    static submit(form: Form)
    {
        let element: HTMLFormElement = <HTMLFormElement>form.element;
        element.submit();
    }

    static descendants(form: Form): Form[]
    {
        let descendants = []
        for (let child of form.children) {
            descendants.push(child);
            for (let descendant of FormUtil.descendants(child)) {
                descendants.push(descendant);
            }
        }
        return descendants;
    }

    static setData(data: any, form: Form)
    {

    }

    static getData(data: any, form: Form)
    {

    }
}
