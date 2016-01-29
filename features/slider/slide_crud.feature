Feature: Slide CRUD
  In order to create slide in backend
  As a user
  I want to see, create, edit and delete slide

  Background:
    Given admin user

  Scenario: See Index
    Given I am logged in as admin
    Given I am on "/admin/enhavo/slider/slide/index"
    Then I should be on "/admin/enhavo/slider/slide/index"
    And the response status code should be 200