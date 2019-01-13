
export class View
{
    private parent: View;

    private children: View[] = [];

    private name: string;

    private id: string;

    constructor(parent: View, children: View[], name: string, id: string) {
        this.parent = parent;
        this.children = children;
        this.name = name;
        this.id = id;
    }
}