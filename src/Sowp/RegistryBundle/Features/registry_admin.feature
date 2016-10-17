Feature: Registry admin panel
  In order to maintain the registry on the site
  As an admin
  I need to be able to add/edit/delete/list registry

  Background:
    Given the database is clean

  Scenario: List registry
    Given The registry "Umowy" should exists
    And The registry "Przedmioty" should exists
    And I am logged in
    When I am on "/admin/registry/"
    Then I should see a table ".table" with body:
      | Id   | Name        | Actions   |
      | 1    | Umowy       |           |
      | 2    | Przedmioty  |           |

##  @javascript
#  Scenario: Add registry
#    Given I am logged in
#    And I am on "/admin/article/"
#    When I follow "Create a new entry"
#    And I fill in the following:
#      | Title     | Emancypacja pastafarian        |
#      | Content   | Jest to bardzo drażliwa sprawa |
#      | Edit note | Utworzenie podstrony           |
##    And I fill in select2 input "Collection" with "Rzodkiewka" and select "Rzodkiewka (NEW)"
#    And I press "Create"
#    Then I should be on "/admin/article/1"
#    And I should see "Emancypacja pastafarian"
#    And I should see "Jest to bardzo drażliwa sprawa"

  Scenario: Delete registry
    Given I am logged in
    And The registry "Umowy" should exists
    And I am on "/admin/registry/"
    When I follow "Show"
    And I press "Delete"
    Then I should be on "/admin/registry/"
    Then I should see a table ".table" with body:
      | Id   | Name   | Actions   |

  Scenario: Show registry
    Given I am logged in
    And The registry "Umowy" should exists
    And have attributes:
      | Name       | Type  | Description |
      | Nr         | text  | Wyjasnienie |
      | Kontrahent | text  |             |
      | Data       | text  |             |
      | Plik       | file  |             |
    When I am on "/admin/registry/"
    And I follow "Show"
    Then I should see a table "#table-info" with body:
      | Id           | 1      |
      | Name         | Umowy  |
      | Type         | table  |
      | Description  |        |
    Then I should see a table "#panel-attributes table" with body:
      | Name        | Description    |
      | Nr          | Wyjasnienie    |
      | Kontrahent  | No description |
      | Data        | No description |
      | Plik        | No description |

  Scenario: Edit registry
    Given I am logged in
    And The registry "Umowy" should exists
    And have attributes:
      | Name       | Type  | Description |
      | Nr         | text  | Wyjasnienie |
      | Kontrahent | text  |             |
    And I am on "/admin/registry/"
    When I follow "Edit"
    And I fill in the following:
      | Name      | Umowy operacyjne      |
    And I press "Edit"
    Then I should be on "/admin/registry/1"
    And I should see "Umowy operacyjne"
