Feature: User Login
  In order to login into user section
  As a normal user
  I want to enter my email and password to login

  Background:
    Given no active session
    Given following users
      | username   | email             | password   | roles      |
      | peter      | peter@enhavo.com  | savePW     | ROLE_ADMIN |
      | paul       | paul@enhavo.com   | userPW     | ROLE_USER  |

  Scenario: Redirect to login page
    Given I am on "/user/profile"
    Then I should be on "/user/login"

  Scenario: Log in with bad credentials
    Given I am on "/user/login"
    When I fill in the following:
      | username | paul   |
      | password | notMyPW |
    And I press "Log in"
    Then I should be on "/user/login"
    
  Scenario: Log in correctly
    Given I am on "/user/login"
    When I fill in the following:
      | username | peter  |
      | password | savePW |
    And I press "Log in"
    Then I should be on "/user/profile"