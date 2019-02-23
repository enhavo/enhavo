import DataLoader from '@enhavo/app/DataLoader';
import '@enhavo/app/assets/styles/base.scss'
import ActionManager from "@enhavo/app/Action/ActionManager";

export default class Form
{
    private data: any;
    private actionManager: ActionManager;

    constructor(loader: DataLoader, actionManager: ActionManager)
    {
        this.data = loader.load();
        this.actionManager = actionManager;
    }

    getData(): any
    {
        return this.data;
    }
}