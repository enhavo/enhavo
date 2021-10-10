#Feature: User CRUD
#  In order to create users in backend
#  As a user
#  I want to see, create, edit and delete users
#
#  Background:
#    Given admin user
#    Given following users
#      | username          | email             | password | roles      |
#      | peter@enhavo.com  | peter@enhavo.com  | peter    | ROLE_ADMIN |
#      | paul@enhavo.com   | paul@enhavo.com   | paul     | ROLE_USER  |
#    Given I am logged in as admin
#
#  @web
#  Scenario: See Index
#    Given I am on "/admin/enhavo/user/user/index"
#    And I wait for ".view-table-col-text"
#    Then I should be on "/admin/enhavo/user/user/index"
#    And I should see "peter@enhavo.com"
#    And I should see "paul@enhavo.com"
#    And I should see "admin@enhavo.com"
