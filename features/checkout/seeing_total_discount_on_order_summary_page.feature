@checkout
Feature: Seeing order total discount on order summary page
  In order to see what kind of discounts I received
  As a Customer
  I want to be able to see total discount on the order summary page

  Background:
    Given the store operates on a single channel in "France"
    And the store has a product "The Sorting Hat" priced at "$20.00"
    And the store ships everywhere for free
    And the store allows paying offline
    And there is a promotion "Holiday promotion"
    And it gives "20%" discount to every order
    And there is a promotion "All year promotion"
    And it gives "$5.00" discount to every order
    And I am a logged in customer

  @todo
  Scenario: Seeing the total discount on order summary page
    Given I have product "The Sorting Hat" in the cart
    When I specified the shipping address as "Ankh Morpork", "Frost Alley", "90210", "France" for "Jon Snow"
    And I complete order with "UPS" shipping method and "Offline" payment
    Then I should be on the checkout summary step
    And I should see discount total "$8.00"
    And I should see promotion "Holiday promotion"
    And I should see promotion "All year promotion"
