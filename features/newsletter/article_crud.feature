Feature: Newsletter CRUD
  In order to create newsletters in backend
  As a user
  I want to see, create, edit and delete newsletters

  Background:
    Given admin user

  Scenario: See Index
    Given I am logged in as admin
    Given I am on "/admin/enhavo/newsletter/newsletter/index"
    Then I should be on "/admin/enhavo/newsletter/newsletter/index"
    And the response status code should be 200