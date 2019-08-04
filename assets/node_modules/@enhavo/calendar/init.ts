import * as $ from "jquery";
import CalendarBlock from "@enhavo/calendar/Block/CalendarBlock";
import '@enhavo/calendar/assets/styles/block.scss';

$(() => {
    (new CalendarBlock).init(document.body);
});


