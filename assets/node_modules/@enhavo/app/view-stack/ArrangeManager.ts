import ViewInterface from "./ViewInterface";
import MenuManager from "@enhavo/app/menu/MenuManager";
import MenuList from "@enhavo/app/menu/model/MenuList";

export default class ArrangeManager
{
    private menuManager: MenuManager;

    constructor(menuManager: MenuManager)
    {
        this.menuManager = menuManager;
    }

    resize(views: ViewInterface[])
    {

    }

    arrange(viewsData: ViewInterface[])
    {
        window.setTimeout(() => {
            let views = this.getViews(viewsData);

            if(!this.menuManager.isCustomChange()) {
                if(views.length >= 2) {
                    this.menuManager.close();
                    for(let item of this.menuManager.getItems()) {
                        if((<MenuList>item).close) {
                            (<MenuList>item).close();
                        }
                    }
                } else if(views.length == 0) {
                    this.menuManager.open();
                }
            }

            let hasFocus = false;
            for(let view of views) {
                if(view.focus == true) hasFocus = true;
            }

            if(!hasFocus) {
                if(views.length) {
                    views[views.length - 1].focus = true;
                }
            }

            this.setSize(views);
            this.setPosition(views);
        }, 50);
    }

    private setSize(views: ViewInterface[])
    {
        this.setMinimized(views);

        if(views.length == 1) {
            if(!views[0].minimize) {
                views[0].width = '100%';
            }
        } else if(views.length == 2) {
            if(!views[0].minimize) {
                if(views[1].minimize) {
                    views[0].width = '100%';
                } else {
                    views[0].width = '30%';
                }
            }
        } else if(views.length == 3) {
            if(!views[0].minimize) {
                if(views[1].minimize && views[2].minimize) {
                    views[0].width = '100%';
                } else {
                    views[0].width = '30%';
                }
            }
        }
    }

    private setMinimized(views: ViewInterface[])
    {
        if(views.length == 1) {
            if(!views[0].customMinimized) {
                views[0].minimize = false;
            }
        } else if(views.length == 2) {

            if(!views[0].customMinimized) {
                views[0].minimize = false;
            }

        } else if(views.length == 3) {
            if(!views[0].customMinimized) {
                views[0].minimize = true;
            }
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

    private getViews(views: ViewInterface[]): ViewInterface[]
    {
        let returnData = [];
        for(let view of views) {
            if(!view.removed) {
                returnData.push(view);
            }
        }
        return returnData;
    }
}