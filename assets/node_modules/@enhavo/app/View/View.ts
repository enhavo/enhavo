
export class View
{
    private parent: View;

    private children: View[] = [];

    private name: string;

    private id: number;

    constructor(id: number, name: string, parent: View, children: View[])
    {
        this.parent = parent;
        this.children = children;
        this.name = name;
        this.id = id;
    }

    addChild(view: View)
    {
        this.children.push(view);
    }

    getParent(): View
    {
        return this.parent;
    }
}