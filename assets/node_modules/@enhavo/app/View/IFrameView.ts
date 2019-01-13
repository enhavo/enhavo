import {View} from "./View";

export class IFrameView extends View
{
    getElement() : string
    {
        return '<iframe class="view-iframe"></iframe>'
    }
}