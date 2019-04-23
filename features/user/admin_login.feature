Feature: Admin Login
  In order to login into the backend
  As a admin user
  I want to enter my email and password to login

  Background:
    Given no active session
    Given following users
     | username   | email             | password   | roles      |
     | peter      | peter@enhavo.com  | savePW     | ROLE_ADMIN |
     | paul       | paul@enhavo.com   | userPW     | ROLE_USER  |

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
    Then I should be on "/admin/"

  Scenario: Log in with missing permission
    Given I am on "/admin"
    When I fill in the following:
      | username | paul  |
      | password | userPW |
    And I press "Log in"
    Then the response status code should be 403