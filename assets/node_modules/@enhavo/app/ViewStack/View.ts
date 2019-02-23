import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";

export default class View
{
    private readonly id: number|null;
    private dispatcher: EventDispatcher;

    constructor(dispatcher: EventDispatcher)
    {
        this.id = this.getIdFromUrl();
        this.dispatcher = dispatcher;
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