import { View } from "./View";

export class AjaxView extends View
{
    getElement() : string
    {
        return '<iframe class="view-iframe"></iframe>'
    }
}