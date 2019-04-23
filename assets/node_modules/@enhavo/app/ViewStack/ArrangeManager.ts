import ViewInterface from "./ViewInterface";
import ViewStackData from "./ViewStackData";
import MenuManager from "@enhavo/app/Menu/MenuManager";
import * as $ from "jquery";

export default class ArrangeManager
{
    private views: ViewInterface[];
    private data: ViewStackData;
    private menuManager: MenuManager;
    private minifiedMenu: boolean = false;

    constructor(views: ViewInterface[], data: ViewStackData, menuManager: MenuManager)
    {
        this.views = views;
        this.data = data;
        this.menuManager = menuManager;

        $(window).resize(() => {
            this.resize();
        });
    }

    resize()
    {

    }

    arrange()
    {
        setTimeout(() => {
            let views = this.getViews();

            if(!this.minifiedMenu && this.views.length >= 2) {
                this.menuManager.close();
                this.minifiedMenu = true;
            }

            this.setSize(views);
            this.setPosition(views);
        }, 50);
    }

    private setSize(views: ViewInterface[])
    {
        if(views.length == 1) {
            if(!views[0].customMinimized) {
                views[0].minimize = false;
            }
            views[0].width = '100%';
        } else if(views.length == 2) {
            views[0].width = '30%';
            if(!views[0].customMinimized) {
                views[0].minimize = false;
            }
            views[1].width = 'inherit';
        } else if(views.length == 3) {
            if(!views[0].customMinimized) {
                views[0].minimize = true;
            }
            views[0].width = '30%';
            views[1].width = 'inherit';
            views[2].width = 'inherit';
        }
    }

    private setPosition(views: ViewInterface[])
    {
        for (let view of views) {
            view.position = null;
        }

        let position = 0;
        for (let view of views) {
            if (view.minimize) {
                view.position = position++;
            }
        }

        for (let view of views) {
            if (!view.minimize) {
                view.position = position++;
                if(view.position != null) {
                    for(let children of view.children) {
                        children.position = position++;
                    }
                }
            }
        }
    }

    private getViews(): ViewInterface[]
    {
        let views = [];
        for(let view of this.views) {
            if(!view.removed) {
                views.push(view);
            }
        }
        return views;
    }
}