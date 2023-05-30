# ./vendor/bin/behat -c tests/Integration/Behaviour/behat.yml -s alias --tags update_alias_status_feature
@restore-aliases-before-feature
@clear-cache-before-feature
@clear-cache-after-feature
@update_alias_status_feature
Feature: Add basic alias from Back Office (BO)
  As a BO user
  I need to be able to add new alias with basic information from the BO

  Scenario: I disable multiple aliases
    Given following aliases should exist:
      | id reference | alias  | search | active |
      | alias1       | bloose | blouse | 1      |
      | alias2       | blues  | blouse | 1      |
    When I disable alias with reference "alias1"
    And I disable alias with reference "alias2"
    Then following aliases should exist:
      | id reference | alias  | search | active |
      | alias1       | bloose | blouse | 0      |
      | alias2       | blues  | blouse | 0      |

  Scenario: I enable single alias
    And I enable alias with reference "alias2"
    Then following aliases should exist:
      | id reference | alias  | search | active |
      | alias1       | bloose | blouse | 0      |
      | alias2       | blues  | blouse | 1      |
