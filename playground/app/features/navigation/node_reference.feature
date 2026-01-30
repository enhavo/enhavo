Feature: Node Reference
  In order to store nodes with references
  As i programmer
  I want to store different objects to nodes

  Scenario: Save node with page object
    Given node navigation object
    Given page navigation object
    Given node navigation has type "page"
    Then I add page navigation object to node navigation object
    And I flush doctrine
    And node navigation object content class should be "enhavo_page.page"
    And node navigation object content id should be same as page navigation object

# Add test cases:
# Existing node and new page
# Existing node and existing page
# Change page on existing node
# Add two nodes with content