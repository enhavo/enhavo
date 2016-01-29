Feature: Article CRUD
  In order to create articles in backend
  As a user
  I want to see, create, edit and delete articles

  Background:
    Given admin user

  Scenario: See Index
    Given I am logged in as admin
    Given I am on "/admin/enhavo/article/article/index"
    Then I should be on "/admin/enhavo/article/article/index"
    And the response status code should be 200