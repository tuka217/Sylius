<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Behat\Page\Shop\Cart;

use Behat\Mink\Exception\ElementNotFoundException;
use Sylius\Behat\Page\SymfonyPage;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
class SummaryPage extends SymfonyPage implements SummaryPageInterface
{
    /**
     * {@inheritdoc}
     */
    public function getGrandTotal()
    {
        $grandTotalElement = $this->getElement('grand total');

        return trim(str_replace('Grand total:', '', $grandTotalElement->getText()));
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxTotal()
    {
        $taxTotalElement = $this->getElement('tax total');

        return trim(str_replace('Tax total:', '', $taxTotalElement->getText()));
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingTotal()
    {
        $shippingTotalElement = $this->getElement('shipping total');

        return trim(str_replace('Shipping total:', '', $shippingTotalElement->getText()));
    }

    /**
     * {@inheritdoc}
     */
    public function getPromotionTotal()
    {
        $shippingTotalElement = $this->getElement('promotion total');

        return trim(str_replace('Promotion total:', '', $shippingTotalElement->getText()));
    }

    /**
     * {@inheritdoc}
     */
    public function getItemRegularPrice($productName)
    {
        $regularPriceElement = $this->getElement('product regular price', ['%name%' => $productName]);

        return trim($regularPriceElement->getText());
    }

    /**
     * {@inheritdoc}
     */
    public function getItemDiscountPrice($productName)
    {
        $discountPriceElement = $this->getElement('product discount price', ['%name%' => $productName]);

        return trim($discountPriceElement->getText());
    }

    /**
     * {@inheritdoc}
     */
    public function isItemDiscounted($productName)
    {
        return $this->hasElement('product discount price', ['%name%' => $productName]);
    }

    /**
     * {@inheritdoc}
     */
    public function removeProduct($productName)
    {
        $itemElement = $this->getElement('product row', ['%name%' => $productName]);
        $itemElement->find('css', 'a#remove-button')->click();
    }

    /**
     * {@inheritdoc}
     */
    public function changeQuantity($productName, $quantity)
    {
        $itemElement = $this->getElement('product row', ['%name%' => $productName]);
        $itemElement->find('css', 'input[type=number]')->setValue($quantity);

        $this->getDocument()->pressButton('Save');
    }

    /**
     * {@inheritdoc}
     */
    public function isSingleItemOnPage()
    {
        $items = $this->getElement('cart items')->findAll('css', 'tbody > tr');

        return 1 === count($items);
    }

    /**
     * {@inheritdoc}
     */
    public function isItemWithName($name)
    {
       return $this->findItemWith($name, 'tbody  tr > td > div > a > strong');
    }

    /**
     * {@inheritdoc}
     */
    public function isItemWithVariant($variantName)
    {
       return $this->findItemWith($variantName, 'tbody  tr > td > strong');
    }

    /**
     * {@inheritdoc}
     */
    public function getProductOption($productName, $optionName)
    {
        $itemElement = $this->getElement('product row', ['%name%' => $productName]);

        return $itemElement->find('css', sprintf('li:contains("%s")', ucfirst($optionName)))->getText();
    }

    /**
     * {@inheritdoc}
     */
    public function getInformationAboutEmptyCart()
    {
        return $this->getDocument()->find('css', '.message')->getText();
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'sylius_shop_cart_summary';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'grand total' => '#cart-summary td:contains("Grand total")',
            'promotion total' => '#cart-summary td:contains("Promotion total")',
            'shipping total' => '#cart-summary td:contains("Shipping total")',
            'tax total' => '#cart-summary td:contains("Tax total")',
            'product row' => '#cart-items tbody tr:contains("%name%")',
            'product regular price' => '#cart-items tr:contains("%name%") .regular-price',
            'product discount price' => '#cart-items tr:contains("%name%") .discount-price',
            'total' => '.total',
            'quantity' => '#sylius_cart_items_%number%_quantity',
            'unit price' => '.unit-price',
            'cart items' => '#cart-items',
            'cart summary' => '#cart-summary',
        ]);
    }

    /**
     * @param $attributeName
     * @param $selector
     *
     * @return bool
     *
     * @throws ElementNotFoundException
     */
    private function findItemWith($attributeName, $selector)
    {
        $itemsAttributes = $this->getElement('cart items')->findAll('css', $selector);

        foreach($itemsAttributes as $itemAttribute) {
            if($attributeName === $itemAttribute->getText()) {
                return true;
            }
        }

        return false;
    }
}
