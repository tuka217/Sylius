<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\OrderBundle\Twig;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\OrderBundle\Templating\Helper\CartHelper;
use Sylius\Bundle\OrderBundle\Twig\CartExtension;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
final class CartExtensionSpec extends ObjectBehavior
{
    function let(CartHelper $helper)
    {
        $this->beConstructedWith($helper);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CartExtension::class);
    }

    function it_is_a_twig_extension()
    {
        $this->shouldHaveType(\Twig_Extension::class);
    }
}
