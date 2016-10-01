Feature: Authorization
  In order to prevent making changes by unauthorized persons
  As an user
  I need to have password protected area

  Background:
    Given the database is clean

  Scenario: Unable to register a new user
    When I am on "/register"
    Then the response status code should be 404

  Scenario: User can log to admin panel
    Given there is a user "root" with password "root"
    When I am on the homepage
    And I follow "Login"
    And I fill in the following:
      | username | root |
      | password | root |
    And I press "Log in"
    Then I am on the homepage
    And I should see text matching "Logout"

  Scenario: User can restore a password
    Given there is a user "root" with password "root"
    When I am on "resetting/request"
    And I fill in "username" with "root"
    And I press "Reset password"
    And I open the confirmation link for the user "root"
    And I fill in the following:
      | New password        | rzodkiewka |
      | Repeat new password | rzodkiewka |
    And I press "Change password"
    Then I should be on "/profile/"
    And I should see "The password has been reset successfully"