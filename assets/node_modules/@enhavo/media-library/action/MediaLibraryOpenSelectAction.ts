import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {FrameManager} from "@enhavo/app/frame/FrameManager"
import {Translator} from "@enhavo/app/translation/Translator"
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger"
import {ActionMediaInterface} from "@enhavo/media/action/ActionMediaInterface"
import {MediaForm} from "@enhavo/media/form/model/MediaForm";
import {MediaFileSelectEvent} from "@enhavo/media-library/event/MediaFileSelectEvent";

export class MediaLibraryOpenSelectAction extends AbstractAction implements ActionMediaInterface
{
    private url: string;

    public form: MediaForm;

    constructor(
        private frameManager: FrameManager,
        private translator: Translator,
        private flashMessenger: FlashMessenger,
    ) {
        super();
    }

    execute(): void
    {
        this.frameManager.openFrame({
            url: this.url,
            key: 'media_library_select',
            keepAlive: true,
            parameters: {
                fullName: this.form.fullName
            },
        });
    }

    async mounted()
    {
        let frame = await this.frameManager.getFrame()
        this.frameManager.on('media_file_select', (event) => {
            if (frame.id === (event as MediaFileSelectEvent).parent &&
                this.form.fullName === (event as MediaFileSelectEvent).fullName
            ) {
                for (let file of (event as MediaFileSelectEvent).files) {
                    this.form.addFile(file)
                }
                this.flashMessenger.success(this.translator.trans('enhavo_media_library.select.message.file_selected', {}, 'javascript'));
            }
        });
    }
}
