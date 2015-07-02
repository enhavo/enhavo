Feature: admin
  In order to see the directory structure
  As a UNIX user
  I need to be able to list the current directory's contents

Scenario:
  Given I am in a directory "test"
  When I call "ls"
  Then I will received:
    """
    bar
    foo
    """