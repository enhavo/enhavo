Feature: Admin Login
    In order to log into admin
    As a admin user
    I want to enter my email and password to login

    Background:
        Given admin user
        Given I clear cookies

    @web
    Scenario: Redirect to login page
        And I am on "/admin"
        Then I should be on "/admin/login"
        And I should see "Log in"

    @web
    Scenario: Go to login page
        Given I am on "/admin"
        And I wait for "[name=_username]"
        And I fill in "_username" with "admin@enhavo.com"
        And I fill in "_password" with "admin"
        And I press "#_submit"
        And I wait for "5" seconds
        Then I should be on "/admin/"



#Feature: Admin Login
#    In order to login into the backend
#    As a admin user
#    I want to enter my email and password to login
#
#    Background:
#        Given no active session
#        Given following users
#            | username   | email             | password   | roles      |
#            | peter      | peter@enhavo.com  | savePW     | ROLE_ADMIN |
#            | paul       | paul@enhavo.com   | userPW     | ROLE_USER  |
#
#    Scenario: Redirect to login page
#        Given I am on "/admin"
#        Then I should be on "/admin/login"
#
#    Scenario: Log in with bad credentials
#        Given I am on "/admin/login"
#        When I fill in the following:
#            | username | peter   |
#            | password | notMyPW |
#        And I press "Log in"
#        Then I should be on "/admin/login"
#
#    Scenario: Log in correctly
#        Given I am on "/admin"
#        When I fill in the following:
#            | username | peter  |
#            | password | savePW |
#        And I press "Log in"
#        Then I should be on "/admin/"
#
#    Scenario: Log in with missing permission
#        Given I am on "/admin"
#        When I fill in the following:
#            | username | paul  |
#            | password | userPW |
#        And I press "Log in"
#        Then the response status code should be 403