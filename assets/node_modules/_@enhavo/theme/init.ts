import "@enhavo/theme/assets/styles/base.scss"
import * as $ from "jquery";
import Theme from "./lib/Theme";

$(() => {
    (new Theme()).init(document.body);
});