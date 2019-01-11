
export abstract class View
{
    private _parent: View;

    private _childen: View[] = [];

    private _name: string;

    private _id: string;

    get parent(): View {
        return this._parent;
    }

    set parent(value: View) {
        this._parent = value;
    }

    get name(): string {
        return this._name;
    }

    set name(value: string) {
        this._name = value;
    }

    get id(): string {
        return this._id;
    }

    set id(value: string) {
        this._id = value;
    }

    protected createViewElement(element: HTMLElement)
    {

    }
}