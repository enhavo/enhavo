import { Media } from 'media/Media';
import { Admin } from "app/Admin";
import { Router } from "app/Router";
import { Form } from "app/Form";
import { Translator } from "app/Translator";
import { Templating } from "app/Templating";

class EnhavoAdapter
{
    private static initFormListener(): void
    {
        $(document).on('formOpenAfter', function (event, form) {
            //Media.initUploads(form);
        });
        $(document).on('formListAddItem', function (event, form) {
            //Media.initUploads(form);
        });
    }

    private static initUploads(form:string|HTMLElement)
    {
        $(form).find('.uploadForm').each(function (formIndex, uploadForm: HTMLElement) {
            //Media.initUpload(uploadForm);
        });
    }
}

export default new EnhavoAdapter();