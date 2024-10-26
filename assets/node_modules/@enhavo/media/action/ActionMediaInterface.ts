import {MediaForm} from "@enhavo/media/form/model/MediaForm";
import {ActionInterface} from "@enhavo/app/action/ActionInterface";

export interface ActionMediaInterface extends ActionInterface
{
    form: MediaForm;
}
