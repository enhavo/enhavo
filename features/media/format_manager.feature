Feature: Format Manager
  In order format images
  As a programmer
  I want to resize and use filter for my pictures

  Scenario: Image with fix sizes
    Given A image with "300"px width and "100"px height
    Given A image format setting with "120"px width and "120"px height
    When I apply image format

  Scenario: Image with fix width
    Given A image with "800"px width and "600"px height
    Given A image format setting with "400"px width
    When I apply image format
    Then the image size should be "400"px width and "300"px height

  Scenario: Image with fix height
    Given A image with "600"px width and "300"px height
    Given A image format setting with "150"px height
    When I apply image format
    Then the image size should be "300"px width and "150"px height

  Scenario: Image with max height but image is smaller
    Given A image with "600"px width and "300"px height
    Given A image format setting with "500"px max height
    When I apply image format
    Then the image size should be "600"px width and "300"px height

  Scenario: Image with max height and image is bigger
    Given A image with "400"px width and "800"px height
    Given A image format setting with "400"px max height
    When I apply image format
    Then the image size should be "200"px width and "400"px height

  Scenario: Image with max width but image is smaller
    Given A image with "600"px width and "300"px height
    Given A image format setting with "800"px max width
    When I apply image format
    Then the image size should be "600"px width and "300"px height

  Scenario: Image with max width and image is bigger
    Given A image with "800"px width and "600"px height
    Given A image format setting with "400"px max width
    When I apply image format
    Then the image size should be "400"px width and "300"px height

  Scenario: Image with max width and max height and nothing fit
    Given A image with "800"px width and "600"px height
    Given A image format setting with "400"px max width and "400"px max height
    When I apply image format
    Then the image size should be "400"px width and "300"px height

  Scenario: Image with max width and max height and width fit
    Given A image with "400"px width and "800"px height
    Given A image format setting with "500"px max width and "400"px max height
    When I apply image format
    Then the image size should be "200"px width and "400"px height

  Scenario: Image with max width and max height and height fit
    Given A image with "600"px width and "300"px height
    Given A image format setting with "300"px max width and "400"px max height
    When I apply image format
    Then the image size should be "300"px width and "150"px height