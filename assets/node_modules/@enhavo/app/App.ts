import { DataLoader } from './DataLoader';

export class App
{
    private data: any;

    constructor(loader: DataLoader)
    {
        this.data = loader.load();
    }

    getData(): any
    {
        return this.data;
    }
}