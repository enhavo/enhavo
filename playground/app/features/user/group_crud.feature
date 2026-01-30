Feature: Page CRUD
  In order to create groups in backend
  As a user
  I want to see, create, edit and delete groups

  Background:
    Given admin user

  Scenario: See Index
    Given I am logged in as admin
    Given I am on "/admin/enhavo/user/group/index"
    Then I should be on "/admin/enhavo/user/group/index"
    And the response status code should be 200