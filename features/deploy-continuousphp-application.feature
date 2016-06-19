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
    When I run "./agent deploy application --name=deploy-agent-staging --build=3a4c7c3d-27db-4221-aaf5-401de8aa09c3"
    Then the exit code should be "0"
    And file "./data/packages/deploy-agent-staging/3a4c7c3d-27db-4221-aaf5-401de8aa09c3.tar.gz" should exist
    And file "/tmp/test/application/3a4c7c3d-27db-4221-aaf5-401de8aa09c3/README.md" should exist
    And file "/tmp/test/application/current/continuousphp.package" should match "/tmp/test/application/3a4c7c3d-27db-4221-aaf5-401de8aa09c3/continuousphp.package"
