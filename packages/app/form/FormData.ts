import Tab from "@enhavo/app/form/Tab";

export default class FormData
{
    public tabs: Array<Tab>;
    public cssClass: string;
    public formChanged: boolean = false;
    public resource: any;
}