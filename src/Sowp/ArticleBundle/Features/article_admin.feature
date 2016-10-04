Feature: Article admia panel
  In order to maintain the article on the site
  As an admin
  I need to be able to add/edit/delete/list articles

  Background:
    Given the database is clean

  Scenario: List articles
    Given The article "Emancypacja pastafarian" exists
    Given 34 articles should exist
    When I am logged in
    And I am on "/admin/article/"
    Then I should see a table with 10 rows
    And I should see "Emancypacja pastafarian"
    And I follow "4"
    And I should see a table with 5 rows

#  @javascript
  Scenario: Add article
    Given I am logged in
    And The collection "Prawo" exists
    And I am on "/admin/article/"
    When I follow "Create a new entry"
    And I fill in the following:
      | Title     | Emancypacja pastafarian        |
      | Content   | Jest to bardzo drażliwa sprawa |
      | Edit note | Utworzenie podstrony           |
#    And I fill in select2 input "Collection" with "Rzodkiewka" and select "Rzodkiewka (NEW)"
    And I press "Create"
    Then I should be on "/admin/article/1"
    And I should see "Emancypacja pastafarian"
    And I should see "Jest to bardzo drażliwa sprawa"

  Scenario: Delete article
    Given I am logged in
    And The article "Kwalifikacje" exists
    And I am on "/admin/article/"
    And I should see a table with 1 rows
    When I follow "Show"
    And I press "Delete"
    Then I should be on "/admin/article/1"
    And I am on "/admin/article/"
    And I should see a table with 0 rows

  Scenario: Edit article
    Given I am logged in
    And The article "Kwalifikacje" exists
    And I am on "/admin/article/"
    And I should see a table with 1 rows
    When I follow "Edit"
    And I fill in the following:
      | Title     | Emancypacja pastafarian        |
      | Edit note | Utworzenie podstrony           |
    And I press "Edit"
    Then I should be on "/admin/article/1"
    And I should see "Emancypacja pastafarian"
    And I should not see "Kwalifikacje"
