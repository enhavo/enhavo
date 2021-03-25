import ColumnInterface from "@enhavo/app/grid/column/ColumnInterface";

export default class AbstractColumn implements ColumnInterface
{
    public sortable: boolean;
    public directionDesc: boolean = null;
    public key: string;
    public component: string;
    public condition: string;
    public display: boolean;
    public width: number;
}
