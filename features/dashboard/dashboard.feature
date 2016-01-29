Feature: Dashboard
  In order see some information
  As a user
  I can see informations on the dashboard site

  Background:
    Given admin user

  Scenario: See Index
    Given I am logged in as admin
    Given I am on "/admin/enhavo/dashboard"
    Then I should be on "/admin/enhavo/dashboard"
    And the response status code should be 200