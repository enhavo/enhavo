Feature: Format Manager
  In order format images
  As a programmer
  I want to resize and use filter for my pictures

  Scenario: Image with fix sizes
    Given A image with "300"px width and "100"px height
    Given A new filter setting
    Given A filter setting "height" with value "120"
    Given A filter setting "width" with value "120"
    When I apply image on filter "image"
    Then the image size should be "120"px width and "120"px height

  Scenario: Image with fix width
    Given A image with "800"px width and "600"px height
    Given A new filter setting
    Given A filter setting "width" with value "400"
    When I apply image on filter "image"
    Then the image size should be "400"px width and "300"px height

  Scenario: Image with fix height
    Given A image with "600"px width and "300"px height
    Given A new filter setting
    Given A filter setting "height" with value "150"
    When I apply image on filter "image"
    Then the image size should be "300"px width and "150"px height

  Scenario: Image with max height but image is smaller
    Given A image with "600"px width and "300"px height
    Given A new filter setting
    Given A filter setting "myxHeight" with value "500"
    When I apply image on filter "image"
    Then the image size should be "600"px width and "300"px height

  Scenario: Image with max height and image is bigger
    Given A image with "400"px width and "800"px height
    Given A new filter setting
    Given A filter setting "maxHeight" with value "400"
    When I apply image on filter "image"
    Then the image size should be "200"px width and "400"px height

  Scenario: Image with max width but image is smaller
    Given A image with "600"px width and "300"px height
    Given A new filter setting
    Given A filter setting "maxWidth" with value "800"
    When I apply image on filter "image"
    Then the image size should be "600"px width and "300"px height

  Scenario: Image with max width and image is bigger
    Given A image with "800"px width and "600"px height
    Given A new filter setting
    Given A filter setting "maxWidth" with value "400"
    When I apply image on filter "image"
    Then the image size should be "400"px width and "300"px height

  Scenario: Image with max width and max height and nothing fit
    Given A image with "800"px width and "600"px height
    Given A new filter setting
    Given A filter setting "maxWidth" with value "400"
    Given A filter setting "maxHeight" with value "400"
    When I apply image on filter "image"
    Then the image size should be "400"px width and "300"px height

  Scenario: Image with max width and max height and width fit
    Given A image with "400"px width and "800"px height
    Given A new filter setting
    Given A filter setting "maxHeight" with value "400"
    Given A filter setting "maxWidth" with value "500"
    When I apply image on filter "image"
    Then the image size should be "200"px width and "400"px height

  Scenario: Image with max width and max height and height fit
    Given A image with "600"px width and "300"px height
    Given A new filter setting
    Given A filter setting "maxHeight" with value "400"
    Given A filter setting "maxWidth" with value "300"
    When I apply image on filter "image"
    Then the image size should be "300"px width and "150"px height

  Scenario: Image with inset mode on landscape image
    Given A image with "600"px width and "300"px height
    Given A new filter setting
    Given A filter setting "height" with value "100"
    Given A filter setting "width" with value "100"
    Given A filter setting "mode" with value "inset"
    When I apply image on filter "image"
    Then the image size should be "100"px width and "100"px height

  Scenario: Image with inset mode on portrait image
    Given A image with "300"px width and "600"px height
    Given A new filter setting
    Given A filter setting "height" with value "100"
    Given A filter setting "width" with value "100"
    Given A filter setting "mode" with value "inset"
    When I apply image on filter "image"
    Then the image size should be "100"px width and "100"px height