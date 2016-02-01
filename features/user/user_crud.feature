Feature: Page CRUD
  In order to create users in backend
  As a user
  I want to see, create, edit and delete users

  Background:
    Given admin user

  Scenario: See Index
    Given I am logged in as admin
    Given I am on "/admin/enhavo/user/user/index"
    Then I should be on "/admin/enhavo/user/user/index"
    And the response status code should be 200