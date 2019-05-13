import * as $ from "jquery";
import FormElementEvent from '@enhavo/form/Event/FormElementEvent';
import FormConvertEvent from '@enhavo/form/Event/FormConvertEvent';
import FormInsertEvent from '@enhavo/form/Event/FormInsertEvent';
import FormReleaseEvent from '@enhavo/form/Event/FormReleaseEvent';

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



