Feature: Contact Controller
  In order to send a contact message
  As a user
  I want go to the contact form page and send a formular

#  Scenario: Visit contact form page
#    Given I am on "/contact/contact/submit"
#    Then I should be on "/contact/contact/submit"
#    And the response status code should be 200

#  Scenario: Send contact form
#    Given I am on "/contact/contact/submit"
#    Then I should be on "/contact/contact/submit"
#    When I fill in the following:
#      | enhavo_contact_contact_email     | 007@mi6.com.uk      |
#      | enhavo_contact_contact_firstName | James               |
#      | enhavo_contact_contact_lastName  | Bond                |
#      | enhavo_contact_contact_message   | Shaken, not stirred |
#    And I press "Send"
#    Then I should get an email on "admin@enhavo.com" with:
#       """
#       New Message!
#       """