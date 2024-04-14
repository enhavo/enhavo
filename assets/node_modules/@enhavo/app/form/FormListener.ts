import $ from "jquery";
import FormElementEvent from '@enhavo/app/form/event/FormElementEvent';
import FormConvertEvent from '@enhavo/app/form/event/FormConvertEvent';
import FormInsertEvent from '@enhavo/app/form/event/FormInsertEvent';
import FormReleaseEvent from '@enhavo/app/form/event/FormReleaseEvent';

export default class FormListener
{
    public static onConvert(callback: (event: FormConvertEvent) => void)
    {
        $('body').on('formConvert', function(event, formEvent: FormConvertEvent) {
            callback(formEvent);
        });
    }

    public static onInsert(callback: (event: FormInsertEvent) => void)
    {
        $('body').on('formInsert', function(event, formEvent: FormInsertEvent) {
            callback(formEvent);
        });
    }

    public static onRelease(callback: (event: FormReleaseEvent) => void)
    {
        $('body').on('formRelease', function(event, formEvent: FormReleaseEvent) {
            callback(formEvent);
        });
    }

    public static onMove(callback: (event: FormReleaseEvent) => void)
    {
        $('body').on('formMove', function(event, formEvent: FormElementEvent) {
            callback(formEvent);
        });
    }

    public static onDrop(callback: (event: FormReleaseEvent) => void)
    {
        $('body').on('formDrop', function(event, formEvent: FormElementEvent) {
            callback(formEvent);
        });
    }

    public static onRemove(callback: (event: FormElementEvent) => void)
    {
        $('body').on('formRemove', function(event, formEvent: FormElementEvent) {
            callback(formEvent);
        });
    }
}



