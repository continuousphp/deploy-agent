@continuousphp
Feature: Deploy a specific build of a configured application provided by continuousphp with a specific pipeline
  
  @filesystem
  Scenario: Deploy a build using the CLI
    Given I am in the "." folder
    And I have the application
      | provider           | continuousphp                            |
      | token              | b52f9c7faf680988f88391b35e5e488883442036 |
      | repositoryProvider | git-hub                                  |
      | repository         | fdewinnetest/deploy-agent                |
      | pipeline           | refs/heads/master                        |
      | name               | deploy-agent-staging                     |
      | path               | /tmp/test/application                    |
    When I run "./agent deploy application --name=deploy-agent-staging --build=3a4c7c3d-27db-4221-aaf5-401de8aa09c3"
    Then the exit code should be "0"
    And file "./data/packages/deploy-agent-staging/3a4c7c3d-27db-4221-aaf5-401de8aa09c3.tar.gz" should exist
    And file "/tmp/test/application/3a4c7c3d-27db-4221-aaf5-401de8aa09c3/README.md" should exist
    And file "/tmp/test/application/current/continuousphp.package" should match "/tmp/test/application/3a4c7c3d-27db-4221-aaf5-401de8aa09c3/continuousphp.package"

  @filesystem
  Scenario: Deploy a build with hooks using CLI
    Given I am in the "." folder
    And I have the application
      | provider           | continuousphp                            |
      | token              | b52f9c7faf680988f88391b35e5e488883442036 |
      | repositoryProvider | git-hub                                  |
      | repository         | fdewinnetest/deploy-agent                |
      | pipeline           | refs/heads/master                        |
      | name               | deploy-agent-staging                     |
      | path               | /tmp/test/application                    |
    When I run "./agent deploy application --name=deploy-agent-staging --build=6b7af102-bac8-43f1-a6d0-ad3dfb15411e"
    Then the exit code should be "0"
    And the output should contain:
      """
      Downloading package...
      Extracting package...
      Applying config from /tmp/test/application/6b7af102-bac8-43f1-a6d0-ad3dfb15411e/continuous.yml
      the application is successfully installed
      the application is going to start
      Starting deploy-agent-staging (6b7af102-bac8-43f1-a6d0-ad3dfb15411e)
      the application has started
      deploy-agent-staging (6b7af102-bac8-43f1-a6d0-ad3dfb15411e) has successfully started
      """