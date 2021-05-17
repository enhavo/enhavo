export class Util
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

    static serializeForm(form: HTMLFormElement)
    {
        let formData = new FormData(form);
        let obj = {};

        // better use forEach in typescript here, otherwise this code may compile into a for loop, which can not handle
        // the map object correctly. This will lead to an empty object.
        formData.forEach((value: any, key: string) => {
            obj[key] = value;
        });

        return obj;
    }
}
