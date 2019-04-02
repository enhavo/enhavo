import ColumnInterface from "@enhavo/app/Grid/Column/ColumnInterface";

export default class AbstractColumn implements ColumnInterface
{
    public sortable: boolean = false;
    public directionDesc: boolean = false;
    public key: string;
    public component: string;
}
