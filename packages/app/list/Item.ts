
export default class Item
{
    public children: Item[];
    public data: any;
    public id: number;
    public expand: boolean = true;
    public dragging: boolean = false;
    public parentProperty: string;
    public positionProperty: string;
    public active: boolean = false;

    public getDescendants(): Item[]
    {
        let descendants = [];
        if(this.children) {
            for(let child of this.children) {
                descendants.push(child);
                for(let descendant of child.getDescendants()) {
                    descendants.push(descendant);
                }
            }
        }
        return descendants;
    }
}