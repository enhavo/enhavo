import Confirm from "@enhavo/app/ui/Confirm";

export default class ViewData
{
    confirm: Confirm = null;
    alert: string = null;
    loading: boolean = false;
    id: number;
    label: string;
    closeable: boolean = false;
}