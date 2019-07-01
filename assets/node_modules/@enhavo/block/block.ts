import * as $ from "jquery";
import "slick-carousel"

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
        });
    }
}

