# ./vendor/bin/behat -c tests/Integration/Behaviour/behat.yml -s alias --tags search_alias_for_association
@restore-aliases-before-feature
@clear-cache-before-feature
@clear-cache-after-feature
@search_alias_for_association
Feature: Search alias search terms to associate them in the BO
  As a BO user
  I need to be able to search for alias search terms in the BO to associate them

  Scenario: I disable multiple aliases
    Given I add alias with following information:
      | alias  | large |
      | search | big   |
    And I add alias with following information:
      | alias  | bloom   |
      | search | blossom |
    And following aliases should exist:
      | id reference | alias  | search  |
      | alias1       | bloose | blouse  |
      | alias2       | blues  | blouse  |
      | alias3       | large  | big     |
      | alias4       | bloom  | blossom |
    When I search for alias search term matching "blou" I should get following results:
      | searchTerm |
      | blouse     |
    When I search for alias search term matching "blouse" I should get following results:
      | searchTerm |
      | blouse     |
    When I search for alias search term matching "big" I should get following results:
      | searchTerm |
      | big        |
    When I search for alias search term matching "blo" I should get following results:
      | searchTerm     |
      | blossom,blouse |
