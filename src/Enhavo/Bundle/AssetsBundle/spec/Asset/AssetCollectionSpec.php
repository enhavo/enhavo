<?php

namespace spec\Enhavo\Bundle\AssetsBundle\Asset;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AssetCollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Enhavo\Bundle\AssetsBundle\Asset\AssetCollection');
    }

    function it_add_asset_to_collection_and_get_a_list_in_right_dependency_order()
    {
        $this->add('app', '@EnhavoAppBundle/Resources/js/app.js', ['jquery', 'jquery-ui'])->shouldReturn($this);
        $this->add('jquery-ui', '@EnhavoAssetsBundle/Resources/js/jquery-ui/jquery-ui.js', ['jquery'])->shouldReturn($this);
        $this->add('jquery', '@EnhavoAssetsBundle/Resources/js/jquery/jquery.js')->shouldReturn($this);
        $this->add('base', '@EnhavoAssetsBundle/Resources/js/jquery/base.js')->shouldReturn($this);

        $this->getList()->shouldReturn([
            '@EnhavoAssetsBundle/Resources/js/jquery/jquery.js',
            '@EnhavoAssetsBundle/Resources/js/jquery-ui/jquery-ui.js',
            '@EnhavoAppBundle/Resources/js/app.js',
            '@EnhavoAssetsBundle/Resources/js/jquery/base.js'
        ]);
    }
}
