#Feature: Admin Login
#    In order to log into admin
#    As a admin user
#    I want to enter my email and password to login
#
#    Background:
#        Given admin user
#        Given following users
#            | username          | email             | password | roles      |
#            | peter@enhavo.com  | peter@enhavo.com  | peter    | ROLE_ADMIN |
#            | paul@enhavo.com   | paul@enhavo.com   | paul     | ROLE_USER  |
#        Given I clear cookies
#
#    @web
#    Scenario: Redirect to login page
#        And I am on "/admin"
#        Then I should be on "/admin/login"
#        And I should see "Log in"
#
#    @web
#    Scenario: Log in with bad credentials
#        Given I am on "/admin/login"
#        And I wait for "[name=_email]"
#        And I fill in "_email" with "admin@enhavo.com"
#        And I fill in "_password" with "wrong_password"
#        And I press "#_submit"
#        And I wait for ".error"
#        Then I should be on "/admin/login"
#        Then I should see "Bad credentials"
#
#    @web
#    Scenario: Go to login page
#        Given I am on "/admin"
#        And I wait for "[name=_email]"
#        And I fill in "_email" with "admin@enhavo.com"
#        And I fill in "_password" with "admin"
#        And I press "#_submit"
#        And I wait for ".app-toolbar" seconds
#        Then I should be on "/admin/"
