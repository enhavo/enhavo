

export default class View
{
    private id: number|null;

    constructor() {
        this.id = this.getIdFromUrl();
    }

    private getIdFromUrl(): number|null
    {
        let url = new URL(window.location.href);
        let id = parseInt(url.searchParams.get("view_id"));
        if(id > 0) {
            return id
        }
        return 0;
    }

    getId(): number|null
    {
        return this.id;
    }

    isRoot()
    {
        return this.id == 0;
    }
}