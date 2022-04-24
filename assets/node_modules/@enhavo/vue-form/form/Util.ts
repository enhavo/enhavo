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
        return new FormData(form);
    }
}
