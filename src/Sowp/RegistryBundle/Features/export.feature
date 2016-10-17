Feature: Export CSV
  In order to allow export data
  As an client and admin
  I need to be able to generate and download file

  Background:
    Given the database is clean

  Scenario: Export CSV user
    And The registry "Umowy" should exists
    And have attributes:
      | Name  | Type |
      | Nr    | text |
      | Tresc | text |
    And have rows:
      | Nr  | Tresc |
      | 1   | AA    |
      | 2   | BB    |
    And I am on "/registry"
    When I follow "Umowy"
    And I follow "Export as CSV"
    Then the header "Content-Disposition" should be equal to 'attachment; filename="umowy.csv"'
    And the header "Content-Type" should contain "text/csv"
    And the response should contain "Nr"
    And the response should contain "Tresc"
    And the response should contain "1"
    And the response should contain "2"
    And the response should contain "AA"
    And the response should contain "BB"

  Scenario: Export CSV admin
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
    When I follow "Export as CSV"
    Then the header "Content-Disposition" should be equal to 'attachment; filename="umowy.csv"'
    And the header "Content-Type" should contain "text/csv"
