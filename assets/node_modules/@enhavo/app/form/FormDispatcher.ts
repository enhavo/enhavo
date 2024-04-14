import $ from "jquery";
import FormElementEvent from '@enhavo/app/form/event/FormElementEvent';
import FormConvertEvent from '@enhavo/app/form/event/FormConvertEvent';
import FormInsertEvent from '@enhavo/app/form/event/FormInsertEvent';
import FormReleaseEvent from '@enhavo/app/form/event/FormReleaseEvent';

export default class FormDispatcher
{
    public static dispatchMove(element: HTMLElement)
    {
        let event = new FormElementEvent(element);
        $('body').trigger('formMove', event);
        return event;
    }

    public static dispatchDrop(element: HTMLElement)
    {
        let event = new FormElementEvent(element);
        $('body').trigger('formDrop', event);
        return event;
    }

    public static dispatchConvert(element: string)
    {
        let event = new FormConvertEvent(element);
        $('body').trigger('formConvert', event);
        return event;
    }

    public static dispatchInsert(element: HTMLElement)
    {
        let event = new FormInsertEvent(element);
        $('body').trigger('formInsert', event);
        return event;
    }

    public static dispatchRelease(element: HTMLElement)
    {
        let event = new FormReleaseEvent(element);
        $('body').trigger('formRelease', event);
        return event;
    }

    public static dispatchRemove(element: HTMLElement)
    {
        let event = new FormElementEvent(element);
        $('body').trigger('formRemove', event);
        return event;
    }
}
