Feature: Metadata
  In order to have a model with meta data
  We want to receive the data

  Background:
    Given search metadata factory

  Scenario: Fetch information
    Given search resource
    Given get metadata result from factory for search resource
    Then the metadata result should be ok