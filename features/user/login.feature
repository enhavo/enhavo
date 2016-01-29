Feature: Login
  In order to login into the backend
  As a user
  I want to enter my email and password to login

  Background:
    Given following users
     | username   | email             | password   |
     | peter      | peter@enhavo.com  | savePW     |

  Scenario: Redirect to login page
    Given I am on "/admin"
    Then I should be on "/admin/login"

  Scenario: Log in with bad credentials
    Given I am on "/admin/login"
    When I fill in the following:
      | username | peter   |
      | password | notMyPW |
    And I press "Log in"
    Then I should be on "/admin/login"
    
  Scenario: Log in correctly
    Given I am on "/admin"
    When I fill in the following:
      | username | peter  |
      | password | savePW |
    And I press "Log in"
    Then I should be on "/admin/enhavo/dashboard"