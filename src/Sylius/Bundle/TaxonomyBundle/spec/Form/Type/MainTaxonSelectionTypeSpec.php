<?php

namespace spec\Sylius\Bundle\TaxonomyBundle\Form\Type;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\FormBuilderInterface;

class MainTaxonSelectionTypeSpec extends ObjectBehavior
{
    function let(RepositoryInterface $taxonRepository )
    {
        $this->beConstructedWith($taxonRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\TaxonomyBundle\Form\Type\MainTaxonSelectionType');
    }

    function it_extends_abstract_type()
    {
        $this->shouldHaveType('Symfony\Component\Form\AbstractType');
    }

    function it_bulids_form(FormBuilderInterface $builder)
    {
        $this->taxonRepository->findAll()->shouldBeCalled();

        $builder->add('main_taxon','choise',Argument::withKey('choices'))->shouldBeCalled();

        $this->bulidForm($builder);
    }
}
