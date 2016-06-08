Feature: Workflow CRUD
  In order to create a workflows in backend
  As a user
  I want to see, create, edit and delete workflows

  Background:
    Given admin user

  Scenario: See Index
    Given I am logged in as admin
    Given I am on "/admin/enhavo/workflow/workflow/index"
    Then I should be on "/admin/enhavo/workflow/workflow/index"
    And the response status code should be 200