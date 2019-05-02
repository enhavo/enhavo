
export default class Item
{
    public children: Item[];
    public data: any;
    public id: number;
    public expand: boolean = true;
    public dragging: boolean = false;
}