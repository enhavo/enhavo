import ViewInterface from "./ViewInterface";
import ViewStackData from "./ViewStackData";

export default class ArrangeManager
{
    private views: ViewInterface[];
    private data: ViewStackData;

    constructor(views: ViewInterface[], data: ViewStackData)
    {
        this.views = views;
        this.data = data;
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
        let minimized = 0;
        for(let view of views) {
            if(view.minimize) {
                view.width = 50;
                view.position = width;
                width = width + view.width;
                minimized++;
            }
        }

        let leftover = this.data.width - width;
        let sizePerView = Math.floor(leftover/(views.length-minimized));
        for(let view of views) {
            if(!view.minimize) {
                view.width = sizePerView;
                view.position = width;
                width = width + view.width;
            }
        }
    }
}