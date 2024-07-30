import {ColumnInterface} from "@enhavo/app/column/ColumnInterface";

export class AbstractColumn implements ColumnInterface
{
    public sortable: boolean;
    public directionDesc: boolean = null;
    public key: string;
    public component: string;
    public model: string;
    public condition: string;
    public display: boolean = true;
    public width: number;
}
