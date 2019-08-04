import Tab from "@enhavo/app/Form/Tab";

export default class FormData
{
    public tabs: Array<Tab>;
    public tab: string;
    public formChanged: boolean = false;
    public resource: any;
}