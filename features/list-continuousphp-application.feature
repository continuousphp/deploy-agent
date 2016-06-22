@continuousphp
Feature: List all configured application
  
  Scenario: List no application using the CLI
    Given I am in the "." folder
    When I run "./agent list applications"
    Then the exit code should be "0"
    And the output should contain:
      """
      No application found
      """
  
  @filesystem
  Scenario: List an existing application using the CLI
    Given I am in the "." folder
    And I have the application
      | provider           | continuousphp                            |
      | token              | b52f9c7faf680988f88391b35e5e488883442036 |
      | repositoryProvider | git-hub                                  |
      | repository         | fdewinnetest/deploy-agent                |
      | pipeline           | refs/heads/master                        |
      | name               | deploy-agent-staging                     |
      | path               | /tmp/test/application                    |
    When I run "./agent list applications"
    Then the exit code should be "0"
    And the output should contain:
      """
      deploy-agent-staging
      """
