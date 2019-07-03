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

            $('[data-hero-slider]').slick({
                nextArrow: '<div class="arrow next"></div>',
                prevArrow: '<div class="arrow prev"></div>',
                fade: true,
                dots:true
            });

            window.onload = function(){
                var paginationPage = parseInt($('.cdp').attr('actpage'), 10);
                $('.cdp_i').on('click', function(){
                    var go = $(this).attr('href').replace('#!', '');
                    if (go === '+1') {
                        paginationPage++;
                    } else if (go === '-1') {
                        paginationPage--;
                    }else{
                        paginationPage = parseInt(go, 10);
                    }
                    $('.cdp').attr('actpage', paginationPage);
                });
            };


        });
    }
}

