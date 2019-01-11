import {View} from "@enhavo/app-assets/View/View";

export class AjaxView extends View
{
    getElement() : string
    {
        return '<iframe class="view-iframe"></iframe>'
    }
}