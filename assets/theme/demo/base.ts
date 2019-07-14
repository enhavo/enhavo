import "@enhavo/block/init"
import "@enhavo/slider/init"
import "@enhavo/shop/init"
import "@enhavo/article/init"
import "@enhavo/calendar/init"

import Theme from "./lib/Theme";
import * as $ from "jquery";

$(() => {
    (new Theme()).init(document.body);
});