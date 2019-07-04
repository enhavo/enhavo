import * as $ from "jquery";
import "slick-carousel"
// import "fullcalendar";

export default class Block
{
    public init()
    {
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

            // setTimeout(function() {
            //     let calendarEl = document.getElementById('calendar');
            //     let calendar = new FullCalendar.Calendar(calendarEl, {
            //         plugins: [ 'dayGrid' ]
            //     });
            //     calendar.render();
            // },2000);
        });
    }
}

