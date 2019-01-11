import {View} from "@enhavo/app-assets/View/View";

export class IFrameView extends View
{
    getElement() : string
    {
        return '<iframe class="view-iframe"></iframe>'
    }
}