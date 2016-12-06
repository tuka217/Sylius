<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\CoreBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\CoreBundle\Form\DataTransformer\ProductTaxonCollectionToTaxonCollectionTransformer;
use Sylius\Component\Core\Model\ProductTaxonInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @author Anna Walasek <anna.walasek@lakion.com>
 */
final class ProductTaxonCollectionToTaxonCollectionTransformerSpec extends ObjectBehavior
{
    function let(FactoryInterface $productTaxonFactory)
    {
        $this->beConstructedWith($productTaxonFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductTaxonCollectionToTaxonCollectionTransformer::class);
    }
    
    function it_implements_data_transformer_interface()
    {
        $this->shouldImplement(DataTransformerInterface::class);
    }

    function it_transforms_product_taxons_collection_to_taxon_collection(
        ProductTaxonInterface $firstProductTaxon,
        ProductTaxonInterface $secondProductTaxon,
        TaxonInterface $firstTaxon,
        TaxonInterface $secondTaxon
    ) {
        $productTaxons = new ArrayCollection([$firstProductTaxon->getWrappedObject(), $secondProductTaxon->getWrappedObject()]);

        $firstProductTaxon->getTaxon()->willReturn($firstTaxon);
        $secondProductTaxon->getTaxon()->willReturn($secondTaxon);

        $taxons = new ArrayCollection([$firstTaxon->getWrappedObject(), $secondTaxon->getWrappedObject()]);

        $this->transform($productTaxons)->shouldBeCollection($taxons);
    }
    
    function it_throws_unexpected_type_exception_during_transform(ProductTaxonInterface $productTaxon)
    {
        $this->shouldThrow(UnexpectedTypeException::class)->during('transform', [$productTaxon]);
    }

    function it_returns_empty_collection_during_transform()
    {
        $this->transform(null)->shouldBeCollection(new ArrayCollection());
    }

    function it_transforms_taxons_collection_to_product_taxons_collection(
        ProductTaxonInterface $productTaxon,
        TaxonInterface $taxon,
        FactoryInterface $productTaxonFactory
    ) {
        $productTaxons = new ArrayCollection([$productTaxon->getWrappedObject()]);
        $taxons = new ArrayCollection([$taxon->getWrappedObject()]);

        $productTaxonFactory->createNew()->willReturn($productTaxon);
        $productTaxon->setTaxon($taxon)->shouldBeCalled();

        $this->reverseTransform($taxons)->shouldBeCollection($productTaxons);
    }

    function it_throws_unexpected_type_exception_during_reversing_transformation(TaxonInterface $taxon)
    {
        $this->shouldThrow(UnexpectedTypeException::class)->during('reverseTransform', [$taxon]);
    }

    function it_returns_empty_collection_during_reverse_transform(ArrayCollection $taxons)
    {
        $this->reverseTransform(null)->shouldBeCollection(new ArrayCollection());
    }

    /**
     * {@inheritdoc}
     */
    public function getMatchers()
    {
        return [
            'beCollection' => function ($subject, $key) {
                if (!$subject instanceof Collection || !$key instanceof Collection) {
                    return false;
                }

                if ($subject->count() !== $key->count()) {
                    return false;
                }

                foreach ($subject as $subjectElement) {
                    if (!$key->contains($subjectElement)) {
                        return false;
                    }
                }

                return true;
            },
        ];
    }
}
