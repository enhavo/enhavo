import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import $ from "jquery";
import InitializerInterface from "@enhavo/app/InitializerInterface";

export default class CalendarBlock implements InitializerInterface
{
    public init(element: HTMLElement)
    {
        let calendarEl = <HTMLElement><unknown>$(element).find('#calendar').get(0);
        if(calendarEl) {
            let calendar = new Calendar(calendarEl, {
                plugins: [ dayGridPlugin, timeGridPlugin, listPlugin ],
                aspectRatio: 1.5
            });

            let event = $('[data-event-container]').find('[data-event]');
            event.each(function() {
                let eventTitle = $(this).data('appointment-title');
                let eventStart = $(this).data('appointment-start');
                let eventEnd = $(this).data('appointment-end');
                let eventUrl = $(this).data('appointment-url');

                calendar.addEvent({ title: eventTitle, start: eventStart, end: eventEnd, url: eventUrl});
            });
            calendar.render();
        }
    }
}