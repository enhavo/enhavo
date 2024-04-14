import $ from "jquery";
import CalendarBlock from "@enhavo/calendar/block/CalendarBlock";
import '@enhavo/calendar/assets/styles/block.scss';

$(() => {
    (new CalendarBlock).init(document.body);
});


