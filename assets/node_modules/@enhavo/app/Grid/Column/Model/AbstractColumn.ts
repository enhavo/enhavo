import ColumnInterface from "@enhavo/app/Grid/Column/ColumnInterface";

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
