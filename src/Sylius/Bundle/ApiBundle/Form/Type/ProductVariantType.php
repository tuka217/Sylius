<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ApiBundle\Form\Type;

use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType as BaseProductVariantType;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\CollectionToStringTransformer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Anna Walasek <anna.walasek@lakion.com>
 */
final class ProductVariantType extends AbstractType
{

    /**
     * @var EventSubscriberInterface
     */
    private $oldChannelPricingFormSubscriber;

    public function __construct(EventSubscriberInterface $oldChannelPricingFormSubscriber)
    {
        $this->oldChannelPricingFormSubscriber = $oldChannelPricingFormSubscriber;
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT , function (FormEvent $event) {
            $data = $event->getData();

            if (!array_key_exists('onHand', $data)) {
                $data['onHand'] = 0;
                $event->setData($data);
            }
        });

        $builder->getEventDispatcher()->removeSubscriber($this->oldChannelPricingFormSubscriber);

       // $builder->addEventSubscriber($this->newChannelPricingFormSubscriber);

        $builder->get('channelPricings')->addModelTransformer(new CollectionToStringTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseProductVariantType::class;
    }
}
