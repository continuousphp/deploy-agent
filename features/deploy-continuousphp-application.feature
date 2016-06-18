@continuousphp
Feature: Deploy a specific build of a configured application provided by continuousphp with a specific pipeline
  
  Scenario: Deploy a build using the CLI
    Given I am in the "." folder
    And I have the application
      | provider           | continuousphp                            |
      | token              | b52f9c7faf680988f88391b35e5e488883442036 |
      | repositoryProvider | git-hub                                  |
      | repository         | fdewinnetest/deploy-agent               |
      | pipeline           | refs/heads/master                        |
      | name               | deploy-agent-staging                     |
      | path               | /tmp/test/application                    |
    When I run "./agent deploy application --name=deploy-agent-staging --build=latest"
    Then the exit code should be "0"
