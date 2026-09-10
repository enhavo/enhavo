
export class Translator
{
    private data: object;

    public setData(data: object)
    {
        this.data = data;
    }

    public trans(value: string, parameters: object = null, translationDomain: string = 'messages')
    {
        if (value === null) {
            return null;
        }

        if (this.data && this.data.hasOwnProperty(translationDomain) && this.data[translationDomain].hasOwnProperty(value)) {
            value = this.data[translationDomain][value];
        }

        return this.replacePlaceholder(value, parameters);
    }

    private replacePlaceholder(value: string, parameters: object = null)
    {
        if (parameters !== null) {
            for (const key in parameters) {
                if (parameters.hasOwnProperty(key) && parameters[key] !== null) {
                    value = value.replace(new RegExp('%' + key + '%', 'g'), parameters[key]);
                }
            }
        }

        // Replace any leftover placeholders with empty string
        value = value.replace(/%[a-zA-Z0-9_]+%/g, '').trim();

        return value;
    }
}
