import generateId from "uuid/v4";

export class Frame
{
    constructor(options: object = {})
    {
        Object.assign(this, options);
        if (this.id === undefined) {
            this.id = generateId();
        }
    }

    // options
    id: string;
    url: string;
    key: string;
    parent: string;
    label: string = '';
    wait: boolean = true;
    keepAlive: boolean = false;
    display: boolean = true;
    parameters: object;
    closeable: boolean = true;
    minimize: boolean = false;
    position: number = 0;

    // meta
    width: number = null;
    loaded: boolean = false;
    keepMinimized: boolean = false;
    focus: boolean = false;
    removed: boolean = false;


    getOptions(): object
    {
        return {
            id: this.id,
            url: this.url,
            key: this.key,
            parent: this.parent,
            label: this.label,
            wait: this.wait,
            keepAlive: this.keepAlive,
            display: this.display,
            parameters: this.parameters,
            closeable: this.closeable,
            minimize: this.minimize,
            position: this.position,
        }
    }
}
