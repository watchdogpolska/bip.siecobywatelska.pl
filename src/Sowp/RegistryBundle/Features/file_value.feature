Feature: File value
  In order to attach file to row
  As an admin and user
  I need to be able to:
    - create registry with file attribute
    - add a row with file
    - see the row with the file of the administrative panel
    - see the row with the file as user

  Background:
    Given the database is clean
    Given the upload directory is clean

##  @javascript
#  Scenario: Create registry with file
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

  Scenario: Add row with file
    Given I am logged in
    And The registry "Umowy" should exists
    And have attributes:
      | Name  | Type |
      | Nr    | text |
      | Tresc | text |
      | Plik  | file |
    And I am on "/admin/row/1"
    When I follow "Create a new entry"
    And I fill in the following:
      | Nr        | 3        |
      | Tresc     | CC       |
    And I attach the file "doc.doc" to "Plik"
    And I press "Create"
    Then I should be on "/admin/row/1/1"
    And I should see "Umowy"
    And I should see "CC"
    And I follow "doc.doc"
    And I should be on "/uploads/doc.doc"


  Scenario: Rename file on duplicate
    Given I am logged in
    And The registry "Umowy" should exists
    And have attributes:
      | Name  | Type |
      | Nr    | text |
      | Tresc | text |
      | Plik  | file |
    And I am on "/admin/row/1"
    When I follow "Create a new entry"
    And I fill in the following:
      | Nr        | 3        |
      | Tresc     | CC       |
    And I attach the file "doc.doc" to "Plik"
    And I press "Create"
    And I am on "/admin/row/1"
    And I follow "Create a new entry"
    And I fill in the following:
      | Nr        | 3        |
      | Tresc     | CC       |
    And I attach the file "doc.doc" to "Plik"
    And I press "Create"
    Then I should be on "/admin/row/1/2"
    And I follow "doc.doc"
    And I should be on "/uploads/doc-2.doc"