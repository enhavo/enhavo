import "@enhavo/theme/assets/styles/base.scss"
import $ from "jquery";
import Theme from "./lib/Theme";

$(() => {
    (new Theme()).init(document.body);
});