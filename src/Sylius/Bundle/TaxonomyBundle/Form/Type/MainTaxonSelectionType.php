<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\TaxonomyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * It creates select form for all taxonomies.
 *
 * @author Anna Walasek <anna.walasek@lakion.com>
 */
class MainTaxonSelectionType extends AbstractType
{
    /**
     * @var TaxonRepositoryInterface
     */
    protected $taxonRepository;

    public function __construct(RepositoryInterface $taxonInterface)
    {
        $this->taxonInterface = $taxonInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_main_taxon_selection';
    }

    public function bulidForm(FormBuilderInterface $builder)
    {
        $taxons  = $this->taxonRepository
    }
}
