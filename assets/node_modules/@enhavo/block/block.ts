import * as $ from "jquery";
import "slick-carousel"
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

export default class Block
{
    public init()
    {

        // todo use data el
        document.addEventListener('DOMContentLoaded', function() {
            let calendarEl = document.getElementById('calendar');
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
        });

        $(document).ready(function(){
            $('[data-slider]').slick({
                adaptiveHeight: true,
                dots:true,
                nextArrow: '<div class="arrow next"></div>',
                prevArrow: '<div class="arrow prev"></div>'
            });

            $('[data-hero-slider]').slick({
                nextArrow: '<div class="arrow next"></div>',
                prevArrow: '<div class="arrow prev"></div>',
                fade: true,
                dots:true
            });

            $('[data-show-menu]').on('click', function() {
                $('[data-menu-items]').toggle();
                $(this).toggleClass('active');
            });
        });
    }
}

