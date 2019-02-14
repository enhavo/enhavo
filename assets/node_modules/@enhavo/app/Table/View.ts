import DataLoader from '@enhavo/app/DataLoader';

export default class AppView
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