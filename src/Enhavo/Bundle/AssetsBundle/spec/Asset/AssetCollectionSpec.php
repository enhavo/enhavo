<?php

namespace spec\esperanto\AssetsBundle\Asset;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AssetCollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('esperanto\AssetsBundle\Asset\AssetCollection');
    }

    function it_add_asset_to_collection_and_get_a_list_in_right_dependency_order()
    {
        $this->add('app', '@esperantoAppBundle/Resources/js/app.js', ['jquery', 'jquery-ui'])->shouldReturn($this);
        $this->add('jquery-ui', '@esperantoAssetsBundle/Resources/js/jquery-ui/jquery-ui.js', ['jquery'])->shouldReturn($this);
        $this->add('jquery', '@esperantoAssetsBundle/Resources/js/jquery/jquery.js')->shouldReturn($this);
        $this->add('base', '@esperantoAssetsBundle/Resources/js/jquery/base.js')->shouldReturn($this);

        $this->getList()->shouldReturn([
            '@esperantoAssetsBundle/Resources/js/jquery/jquery.js',
            '@esperantoAssetsBundle/Resources/js/jquery-ui/jquery-ui.js',
            '@esperantoAppBundle/Resources/js/app.js',
            '@esperantoAssetsBundle/Resources/js/jquery/base.js'
        ]);
    }
}
