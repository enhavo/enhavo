import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {ImageCropperManager} from "@enhavo/media/manager/ImageCropperManager";

export class MediaCropAction extends AbstractAction
{
    public method: string;

    constructor(
        private imageCropperManager: ImageCropperManager
    ) {
        super();
    }

    execute(): void
    {
        this.imageCropperManager[this.method]();
    }
}
