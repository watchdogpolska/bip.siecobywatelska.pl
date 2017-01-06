Feature: Collection admia panel
  In order to maintain the collections on the site
  As an admin
  I need to be able to add/edit/delete/list collections

  Background:
    Given the database is clean

  Scenario: List collections
    Given The collection "Kwalifikacje" exists
    And 50 collections should exist
    When I am logged in
    And I am on "/admin/collection/"
    Then I should see a table with 51 rows
    Then I should see "Kwalifikacje"

  Scenario: Add collection
    Given I am logged in
    And I am on "/admin/collection/"
    When I follow "Create a new entry"
    And I fill in "Name" with "Kwalifikacje"
    And I press "Create"
    Then I should be on "/admin/collection/1"
    And I should see "Kwalifikacje"

  Scenario: Delete collection
    Given I am logged in
    And The collection "Kwalifikacje" exists
    And I am on "/admin/collection/"
    And I should see a table with 1 rows
    When I follow "Show"
    And I press "Delete"
    Then I should be on "/admin/collection/"
    And I should see a table with 0 rows

  Scenario: Edit collection
    Given I am logged in
    And The collection "Kwalifikacje" exists
    And I am on "/admin/collection/"
    And I should see a table with 1 rows
    When I follow "Edit"
    And the "Name" field should contain "Kwalifikacje"
    And I fill in "Name" with "Szkolenia"
    And I press "Edit"
    Then I should be on "/admin/collection/1"
    And I should see "Szkolenia"
    And I should not see "Kwalifikacje"
