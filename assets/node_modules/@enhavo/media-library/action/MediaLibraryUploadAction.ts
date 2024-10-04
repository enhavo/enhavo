import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";

export class MediaLibraryUploadAction extends AbstractAction
{
    execute(): void
    {
        console.log('trigger upload');
    }
}
