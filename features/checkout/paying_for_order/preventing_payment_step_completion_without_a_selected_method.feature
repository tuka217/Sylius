@checkout
Feature: Preventing payment step completion without a selected method
    In order to be prevent from finish payment step without selected method
    As a Customer
    I want to be prevent from finish payment step without selected method

    Background:
        Given the store operates on a single channel in "United States"
        And the store ships everywhere for free
        And the store has a product "PHP T-Shirt" priced at "$19.99"
        And I am a logged in customer

    @ui
    Scenario: Preventing payment step completion if there are no available methods
        Given I have product "PHP T-Shirt" in the cart
        When I specified the shipping address as "Ankh Morpork", "Frost Alley", "90210", "United States" for "Jon Snow"
        And I proceed selecting "Free" shipping method
        And I do not select any payment method
        Then I should not be able to complete the payment step
        And there should be information about no payment methods available for my order

    @ui
    Scenario: Preventing payment step completion if there are no available methods for a channel
        Given the store has "Cash on Delivery" payment method not assigned to any channel
        And I have product "PHP T-Shirt" in the cart
        When I specified the shipping address as "Ankh Morpork", "Frost Alley", "90210", "United States" for "Jon Snow"
        And I proceed selecting "Free" shipping method
        And I do not select any payment method
        Then I should not be able to complete the payment step
        And there should be information about no payment methods available for my order

    @ui
    Scenario: Preventing payment step completion if a payment method is disabled
        Given the store has a payment method "Offline" with a code "offline"
        And this payment method is disabled
        And I have product "PHP T-Shirt" in the cart
        When I specified the shipping address as "Ankh Morpork", "Frost Alley", "90210", "United States" for "Jon Snow"
        And I proceed selecting "Free" shipping method
        And I do not select any payment method
        Then I should not be able to complete the payment step
        And there should be information about no payment methods available for my order

    @ui
    Scenario: Preventing payment step completion if a payment method is disabled or not assigned to a channel
        Given the store has a payment method "Offline" with a code "offline"
        And this payment method is disabled
        And the store has "Cash on Delivery" payment method not assigned to any channel
        And I have product "PHP T-Shirt" in the cart
        When I specified the shipping address as "Ankh Morpork", "Frost Alley", "90210", "United States" for "Jon Snow"
        And I proceed selecting "Free" shipping method
        And I do not select any payment method
        Then I should not be able to complete the payment step
        And there should be information about no payment methods available for my order