import DataLoader from '@enhavo/app/DataLoader';
import '@enhavo/app/assets/styles/base.scss'
import ActionManager from "@enhavo/app/Action/ActionManager";

export default class Index
{
    private data: any;
    private actionManager: ActionManager;

    constructor(loader: DataLoader)
    {
        this.data = loader.load();
        this.actionManager = new ActionManager(this.data.actions);
    }

    getData(): any
    {
        return this.data;
    }
}