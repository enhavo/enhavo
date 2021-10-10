#Feature: Admin Login
#    In order to log into admin
#    As a user with no admin permission
#    I want to see a error message
#
#    Background:
#        Given following users
#            | username          | email             | password | roles      |
#            | peter@enhavo.com  | peter@enhavo.com  | peter    | ROLE_USER  |
#        Given I clear cookies
#
#    @web
#    Scenario: Log in with missing permission
#        Given I am on "/admin/login"
#        And I wait for "[name=_email]"
#        And I fill in "_email" with "peter@enhavo.com"
#        And I fill in "_password" with "peter"
#        And I press "#_submit"
#        Then I should be on "/admin/login"
