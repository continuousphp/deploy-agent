@continuousphp
Feature: Create an application provided by continuousphp to automate the deployment of a specific pipeline
  
  Scenario: Create an application using the CLI
    Given I am in the "." folder
    When I run "./agent add application --provider=continuousphp --token=cc2efee7-be03-4611-923e-065bc3dd3326 --repository-provider=git-hub --repository=continuousphp/deploy-agent --pipeline=refs/heads/master --name=deploy-agent-staging --path=/tmp/test/application"
    Then the exit code should be "0"
    And I should have the application
      | provider           | continuousphp                        |
      | token              | cc2efee7-be03-4611-923e-065bc3dd3326 |
      | repositoryProvider | git-hub                              |
      | repository         | continuousphp/deploy-agent           |
      | pipeline           | refs/heads/master                    |
      | name               | deploy-agent-staging                 |
      | path               | /tmp/test/application                |