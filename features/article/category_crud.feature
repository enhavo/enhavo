Feature: Article Category CRUD
  In order to create article categories in backend
  As a user
  I want to see, create, edit and delete categories

  Background:
    Given admin user

  Scenario: See Index
    Given I am logged in as admin
    Given I am on "/admin/enhavo/article/category/index"
    Then I should be on "/admin/enhavo/article/category/index"
    And the response status code should be 200