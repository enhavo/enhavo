Feature: Age Filter
    Testing the age filter

    Background:
        Given admin user
        Given table "app_person" is empty
        Given table "app_person" has rows
            | id | name         | birthday   | children |
            | 1  | James Bond   | 1970-02-04 | 0        |
            | 2  | Peter Pan    | 1982-11-10 | 13       |
            | 3  | Darth Vader  | 2035-01-07 | 2        |
        Given I am logged in as admin

#    @web
#    Scenario: Test with no filter
#        And I am on "/admin/app/person/index"
#        And I wait for ".view-table-col-text"
#        Then I should be on "/admin/app/person/index"
#        And I should see "James Bond"
#        And I should see "Peter Pan"
#        And I should see "Darth Vader"