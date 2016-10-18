Feature: Registry view as user
  In order to display data
  As an user
  I need to be able to show regisry list and registry as table/list

  Background:
    Given the database is clean

  Scenario: List registry
    Given The registry "Umowy" should exists
    And The registry "Przedmioty" should exists
    And I am logged in
    When I am on "/registry/"
    Then I should see a table ".table" with body:
      | Name        |
      | Umowy       |
      | Przedmioty  |

  Scenario: Show registry as table
    Given The registry "Umowy" with type "table" should exists
    And have attributes:
      | Name  | Type |
      | Nr    | text |
      | Tresc | text |
    And have rows:
      | Nr  | Tresc |
      | 1   | AA    |
      | 2   | BB    |
    And I am logged in
    When I am on "/registry/"
    And I follow "Umowy"
    Then I should see a table ".table" with body:
      | Nr | Tresc |
      | 1  | AA    |
      | 2  | BB    |

  Scenario: Show registry as list
    Given The registry "Umowy" with type "list" should exists
    And have attributes:
      | Name  | Type |
      | Nr    | text |
      | Tresc | text |
    And have rows:
      | Nr  | Tresc |
      | 1   | AA    |
      | 2   | BB    |
    And I am logged in
    When I am on "/registry/"
    And I follow "Umowy"
    Then I should see a table "#panel-row-1 table" with body:
      | Nr     | 1     |
      | Tresc  | AA    |
    And I should see a table "#panel-row-2 table" with body:
      | Nr     | 2     |
      | Tresc  | BB    |
