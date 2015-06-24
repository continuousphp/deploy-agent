@continuousphp
Feature: Deploy a specific build of a configured application provided by continuousphp with a specific pipeline
  
  Scenario: Deploy a build using the CLI
    Given I am in the "." folder
    And I have the application
      | provider           | continuousphp                            |
      | token              | e391f57ddd27bb37097a5c46a47776289cf1eff7 |
      | repositoryProvider | git-hub                                  |
      | repository         | continuousphp/deploy-agent               |
      | pipeline           | refs/heads/master                        |
      | name               | deploy-agent-staging                     |
      | path               | /tmp/test/application                    |
    When I run "./agent deploy application --name=deploy-agent-staging --release="
    Then the exit code should be "0"
