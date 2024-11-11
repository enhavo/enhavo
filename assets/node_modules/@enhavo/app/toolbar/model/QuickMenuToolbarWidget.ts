import {AbstractToolbarWidget} from "@enhavo/app/toolbar/model/AbstractToolbarWidget";
import {FrameManager} from "@enhavo/app/frame/FrameManager";

export class QuickMenuToolbarWidget extends AbstractToolbarWidget
{
    public menu: Menu[];
    public icon: string;

    constructor(
        private readonly frameManager: FrameManager,
    )
    {
        super();
    }

    public open(menu: Menu)
    {
        if (menu.target == '_frame') {
            this.frameManager.clearFrames().then(() => {
                this.frameManager.addFrame({
                    url: menu.url,
                    label: menu.label,
                    clear: true,
                })
            })
        } else if(menu.target == '_self') {
            window.location.href = menu.url;
        } else if(menu.target == '_blank') {
            window.open(menu.url, '_blank');
        }
    }
}

export class Menu
{
    public target: string;
    public url: string;
    public label: string;
}