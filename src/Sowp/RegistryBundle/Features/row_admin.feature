Feature: Row admia panel
  In order to maintain the row on the site
  As an admin
  I need to be able to add/edit/delete/list rows for register

  Background:
    Given the database is clean

  Scenario: List row
    And The registry "Umowy" should exists
    And have attributes:
      | Name  | Type |
      | Nr    | text |
      | Tresc | text |
    And have rows:
      | Nr  | Tresc |
      | 1   | AA    |
      | 2   | BB    |
    And I am logged in
    When I am on "/admin/registry/"
    And I follow "Show"
    And I follow "Go to the rows list"
    Then I should see a table ".table" with body:
      | Id   | Nr | Tresc | Actions |
      |      | 1  | AA    |         |
      |      | 2  | BB    |         |

  Scenario: Add row
    Given I am logged in
    And The registry "Umowy" should exists
    And have attributes:
      | Name  | Type |
      | Nr    | text |
      | Tresc | text |
    And have rows:
      | Nr  | Tresc |
      | 1   | AA    |
      | 2   | BB    |
    And I am on "/admin/row/1"
    When I follow "Create a new entry"
    And I fill in the following:
      | Nr        | 3        |
      | Tresc     | CC       |
    And I press "Create"
    Then I should be on "/admin/row/1/3"
    And I should see "Umowy"
    And I should see "CC"


  Scenario: Delete row
    Given I am logged in
    And The registry "Umowy" should exists
    And have attributes:
      | Name  | Type |
      | Nr    | text |
      | Tresc | text |
    And have rows:
      | Nr  | Tresc |
      | 1   | AA    |
      | 2   | BB    |
    And I am on "/admin/row/1"
    When I follow "Show"
    And I press "Delete"
    Then I should be on "/admin/row/1/"
    Then I should see a table "table" with body:
      | Id   | Nr | Tresc | Actions |
      |      | 2  | BB    |         |


  Scenario: Show row
    Given I am logged in
    And The registry "Umowy" should exists
    And have attributes:
      | Name  | Type |
      | Nr    | text |
      | Tresc | text |
    And have rows:
      | Nr  | Tresc |
      | 1   | AA    |
      | 2   | BB    |
    When I am on "/admin/row/1"
    And I follow "Show"
    Then I should see a table "#panel-info table" with body:
      | Id           | 1      |
      | Registry     | Umowy  |
    And I should see a table "#panel-values table" with body:
      | Nr     | 1  |
      | Tresc  | AA |


  Scenario: Edit row
    Given I am logged in
    And The registry "Umowy" should exists
    And have attributes:
      | Name  | Type |
      | Nr    | text |
      | Tresc | text |
    And have rows:
      | Nr  | Tresc |
      | 1   | AA    |
      | 2   | BB    |
    When I am on "/admin/row/1"
    And I follow "Edit"
    And I fill in the following:
      | Nr    | 50/2016 |
      | Tresc | CC      |
    And I press "Edit"
    Then I should see "50/2016"
    And I should see "CC"

