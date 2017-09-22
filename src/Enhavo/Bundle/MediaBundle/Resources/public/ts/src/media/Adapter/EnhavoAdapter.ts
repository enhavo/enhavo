import { Media } from 'media/Media';
import { MediaItem } from 'media/Media';
import * as form from 'app/Form'
class EnhavoAdapter
{
    constructor() {
        EnhavoAdapter.initFormListener();
    }

    private static initFormListener(): void
    {
        $(document).on('formOpenAfter', function (event, form) {
            Media.apply(form);
        });
        $(document).on('formListAddItem', function (event, form) {
            Media.apply(form);
        });

        $(document).on('mediaAddItem', function(event, item:MediaItem) {
            let placeholder = $(item.getRow()).find('[data-form-placeholder]').data('form-placeholder');
            form.initReindexableItem(item.getElement(), placeholder);
            form.reindex();
        });
    }

    public initMedia(form:string|HTMLElement)
    {
        Media.apply(form);
    }
}

export let enhavoAdapter = new EnhavoAdapter();