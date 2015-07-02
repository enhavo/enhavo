<?php

namespace spec\enhavo\AssetsBundle\Asset;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AssetCollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('enhavo\AssetsBundle\Asset\AssetCollection');
    }

    function it_add_asset_to_collection_and_get_a_list_in_right_dependency_order()
    {
        $this->add('app', '@enhavoAppBundle/Resources/js/app.js', ['jquery', 'jquery-ui'])->shouldReturn($this);
        $this->add('jquery-ui', '@enhavoAssetsBundle/Resources/js/jquery-ui/jquery-ui.js', ['jquery'])->shouldReturn($this);
        $this->add('jquery', '@enhavoAssetsBundle/Resources/js/jquery/jquery.js')->shouldReturn($this);
        $this->add('base', '@enhavoAssetsBundle/Resources/js/jquery/base.js')->shouldReturn($this);

        $this->getList()->shouldReturn([
            '@enhavoAssetsBundle/Resources/js/jquery/jquery.js',
            '@enhavoAssetsBundle/Resources/js/jquery-ui/jquery-ui.js',
            '@enhavoAppBundle/Resources/js/app.js',
            '@enhavoAssetsBundle/Resources/js/jquery/base.js'
        ]);
    }
}
