import {decode} from 'html-entities';

export class HtmlEntities
{
    static encode(value: string)
    {
        return decode(value);
    }

    static encodeObject(value: object)
    {
        for (let key in value) {
            if (value.hasOwnProperty(key)) {
                if (typeof value[key] === 'string') {
                    value[key] = HtmlEntities.encode(value[key]);
                } else if (typeof value[key] === 'object') {
                    value[key] = HtmlEntities.encodeObject(value[key]);
                }
            }
        }

        return value;
    }
}