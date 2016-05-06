Feature: Page CRUD
  In order to create pages in backend
  As a user
  I want to see, create, edit and delete pages

  Background:
    Given admin user

  Scenario: See Index
    Given I am logged in as admin
    Given I am on "/admin/enhavo/page/page/index"
    Then I should be on "/admin/enhavo/page/page/index"
    And the response status code should be 200