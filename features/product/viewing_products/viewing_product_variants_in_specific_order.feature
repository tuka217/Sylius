@viewing_products
Feature: Viewing a product variants in specific order
    In order to see the most important product's variant as if I will see the product details
    As a Visitor
    I want to see he most important product's variant as default

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a "Wyborowa Vodka" configurable product
        And the product "Wyborowa Vodka" has "Wyborowa Vodka Exquisite" variant priced at "$40.00"
        And the product "Wyborowa Vodka" has "Wyborowa Apple" variant priced at "$12.55"
        And the product "Wyborowa Vodka" has "Wyborowa Deluxe" variant priced at "$65.55"
        And the "Wyborowa Apple" variant is at 1st position

    @ui
    Scenario: Viewing a detailed page with default variant's price
        When I view product "Wyborowa Vodka"
        Then the product price should be "$12.55"
