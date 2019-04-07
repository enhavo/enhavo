import MenuData from "@enhavo/app/Menu/MenuData";


export default class MenuManager
{
    private data: MenuData;

    constructor(data: MenuData)
    {
        this.data = data;
    }

    isOpen(): boolean
    {
        return this.data.open;
    }

    open() {
        this.data.open = true;
    }

    close() {
        this.data.open = false;
    }
}