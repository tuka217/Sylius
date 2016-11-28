<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Behat\Context\Ui;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\External\PaypalExpressCheckoutPageInterface;
use Sylius\Behat\Page\Shop\Checkout\CompletePageInterface;
use Sylius\Behat\Page\Shop\Order\ShowPageInterface;
use Sylius\Behat\Service\Mocker\PaypalApiMocker;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class PaypalContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var PaypalExpressCheckoutPageInterface
     */
    private $paypalExpressCheckoutPage;

    /**
     * @var ShowPageInterface
     */
    private $orderDetails;

    /**
     * @var CompletePageInterface
     */
    private $summaryPage;

    /**
     * @var PaypalApiMocker
     */
    private $paypalApiMocker;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param PaypalExpressCheckoutPageInterface $paypalExpressCheckoutPage
     * @param ShowPageInterface $orderDetails
     * @param CompletePageInterface $summaryPage
     * @param PaypalApiMocker $paypalApiMocker
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        PaypalExpressCheckoutPageInterface $paypalExpressCheckoutPage,
        ShowPageInterface $orderDetails,
        CompletePageInterface $summaryPage,
        PaypalApiMocker $paypalApiMocker,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->paypalExpressCheckoutPage = $paypalExpressCheckoutPage;
        $this->orderDetails = $orderDetails;
        $this->summaryPage = $summaryPage;
        $this->paypalApiMocker = $paypalApiMocker;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @When /^I confirm my order with paypal payment$/
     * @Given /^I have confirmed my order with paypal payment$/
     */
    public function iConfirmMyOrderWithPaypalPayment()
    {
        $this->paypalApiMocker->performActionInApiInitializeScope(function () {
            $this->summaryPage->confirmOrder();
        });
    }

    /**
     * @Then I should be redirected back to PayPal Express Checkout page
     */
    public function iShouldBeRedirectedToPaypalExpressCheckoutPage()
    {
        Assert::true($this->paypalExpressCheckoutPage->isOpen());
    }

    /**
     * @When I sign in to PayPal and pay successfully
     */
    public function iSignInToPaypalAndPaySuccessfully()
    {
        $this->paypalApiMocker->performActionInApiSuccessfulScope(function () {
            $this->paypalExpressCheckoutPage->pay();
        });
    }

    /**
     * @Given /^I have cancelled (?:|my )PayPal payment$/
     * @When /^I cancel (?:|my )PayPal payment$/
     */
    public function iCancelMyPaypalPayment()
    {
        $this->paypalExpressCheckoutPage->cancel();
    }

    /**
     * @Given /^I tried to pay(?:| again)$/
     * @When /^I try to pay(?:| again)$/
     */
    public function iTryToPayAgain()
    {
        $this->paypalApiMocker->performActionInApiInitializeScope(function () {
            $this->orderDetails->pay();
        });
    }

    /**
     * @Then I should be notified that my payment has been cancelled
     */
    public function iShouldBeNotifiedThatMyPaymentHasBeenCancelled()
    {
        $this->assertNotification('Your payment has been cancelled.');

    }

    /**
     * @Then I should be notified that my payment has been completed
     */
    public function iShouldBeNotifiedThatMyPaymentHasBeenCompleted()
    {
        $this->assertNotification('Your payment has been completed.');
    }

    /**
     * @param string $expectedNotification
     */
    private function assertNotification($expectedNotification)
    {
        $notifications = $this->orderDetails->getNotifications();
        $hasNotifications = '';

        foreach ($notifications as $notification) {
            $hasNotifications .= $notification;
            if ($notification === $expectedNotification) {
                return;
            }
        }

        throw new \RuntimeException(sprintf('There is no notificaiton with "%s". Got "%s"', $expectedNotification, $hasNotifications));
    }
}
