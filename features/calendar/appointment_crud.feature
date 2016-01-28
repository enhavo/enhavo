Feature: Appointment CRUD
  In order to create appointments in backend
  As a user
  I want to see, create, edit and delete appointments

  Background:
    Given admin user

  Scenario: See Index
    Given I am logged in as admin
    Given I am on "/admin/enhavo/newsletter/newsletter/index"
    Then I should be on "/admin/enhavo/newsletter/newsletter/index"
    And the response status code should be 200