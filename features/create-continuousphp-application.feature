@continuousphp
Feature: Create an application provided by continuousphp to automate the deployment of a specific pipeline
  
  Scenario: Create an application using the CLI
    Given I am in the "." folder
    When I run "./agent add application --provider=continuousphp --token=b52f9c7faf680988f88391b35e5e488883442036 --repository-provider=git-hub --repository=fdewinnetest/deploy-agent --pipeline=refs/heads/master --name=deploy-agent-staging --path=/tmp/test/application"
    Then the exit code should be "0"
    And I should have the application
      | provider           | continuousphp                            |
      | token              | b52f9c7faf680988f88391b35e5e488883442036 |
      | repositoryProvider | git-hub                                  |
      | repository         | fdewinnetest/deploy-agent               |
      | pipeline           | refs/heads/master                        |
      | name               | deploy-agent-staging                     |
      | path               | /tmp/test/application                    |