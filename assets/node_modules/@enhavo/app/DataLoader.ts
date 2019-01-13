
export class DataLoader
{
    private id: string;

    private data: any;

    private loaded: boolean = false;

    constructor(id: string)
    {
        this.id = id;
    }

    load(): any
    {
        if(!this.loaded) {
            this.data = JSON.parse(document.getElementById(this.id).innerText);
            this.loaded = true;
        }
        return this.data;
    }
}


