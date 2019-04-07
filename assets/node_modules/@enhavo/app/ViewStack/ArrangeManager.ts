import ViewInterface from "./ViewInterface";
import ViewStackData from "./ViewStackData";
import MenuManager from "@enhavo/app/Menu/MenuManager";

export default class ArrangeManager
{
    private views: ViewInterface[];
    private data: ViewStackData;
    private menuManager: MenuManager;

    constructor(views: ViewInterface[], data: ViewStackData, menuManager: MenuManager)
    {
        this.views = views;
        this.data = data;
        this.menuManager = menuManager;
    }

    arrange()
    {
        let views = [];
        for(let view of this.views) {
            if(!view.removed) {
                views.push(view);
            }
        }

        let width = 0;
        let position = 0;
        let minimized = 0;
        for(let view of views) {
            if(view.minimize) {
                view.position = position++;
                width = width + view.width;
                minimized++;
            }
        }

        let leftover = this.data.width - width;
        let sizePerView = Math.floor(leftover/(views.length-minimized));
        for(let view of views) {
            if(!view.minimize) {
                view.width = sizePerView;
                view.position = position++;
                width = width + view.width;
            }
        }

        if(views.length > 1) {
            this.menuManager.close();
        }
    }
}