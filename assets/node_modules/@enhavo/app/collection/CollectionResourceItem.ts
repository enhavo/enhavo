
export class CollectionResourceItem
{
    public id: number;
    public data: any;
    public selected: boolean = false;
    public active: boolean = false;
    public url: string;
    public children: CollectionResourceItem[];
}
