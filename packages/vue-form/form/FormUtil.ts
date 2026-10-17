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

    static submitAsync(form: Form): Promise<Response>
    {
        let element: HTMLFormElement = <HTMLFormElement>form.element;
        let method = element.method.toUpperCase();
        let data = FormUtil.serializeForm(form);
        let url = new URL(element.action, window.origin);

        if (method === 'GET') {
            for (let [key, value] of data.entries()) {
                url.searchParams.append(key, value.toString());
            }
        }

        return fetch(url.toString(), {
            method: method,
            body: method === 'GET' ? null : data,
            headers: {
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
