import {MediaItemForm} from "@enhavo/media/form/model/MediaItemForm";
import {ActionInterface} from "@enhavo/app/action/ActionInterface";

export interface ActionMediaItemInterface extends ActionInterface
{
    form: MediaItemForm;
}
