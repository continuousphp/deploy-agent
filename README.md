Continuous Deploy Agent
=======================

Master :  
[![Master branch status](https://status.continuousphp.com/git-hub/continuousphp/deploy-agent?token=8a3ddb18-d8aa-45af-8e5a-718a7a668dba&branch=master)](https://continuousphp.com/git-hub/continuousphp/deploy-agent)  
Develop :  
[![Develop branch status](https://status.continuousphp.com/git-hub/continuousphp/deploy-agent?token=8a3ddb18-d8aa-45af-8e5a-718a7a668dba&branch=develop)](https://continuousphp.com/git-hub/continuousphp/deploy-agent)

Automated deployment agent to install on your servers.

It manages automated deployment workflows based on webhooks for your projects.

# Requirements

* PHP 5.5+
* PHP mcrypt extension
* **sqlite** or other doctrine compatible SGBD (sqlite is currently the only one officially supported)

:warning: the PHP sqlite extension is not installed by default.

# Installation by Docker ( Container Service )

You can use our [Docker image](https://hub.docker.com/r/continuous/deploy-agent/) as a container service.
The container is isolated from the rest of your Server and you have not to worry about server dependencies or security.

Thanks to report to [Docker-Hub documentation](https://hub.docker.com/r/continuous/deploy-agent/).

# Installation by Composer

* Download [Composer](https://getcomposer.org/download/): `curl -sS https://getcomposer.org/composer.phar -o composer.phar`
* Install the Deploy Agent: `php composer.phar create-project continuousphp/deploy-agent`
* Start using the agent: `cd deploy-agent && ./agent`

## HTTP server config

To setup apache, setup a virtual host that points to the public/ directory of the project and you should be ready to go!
It should be something similar to:

```
<VirtualHost *>
    DocumentRoot /path/to/deploy-agent/public
    <Directory /path/to/deploy-agent/public>
        DirectoryIndex index.php
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
```

Also enable apache mod rewrite in order to support webhook routes

`a2enmod rewrite`

# Usage

## List your applications

```
./agent list applications
```

## Create an application

```
./agent add application [--provider=] [--token=] [--repository-provider=] [--repository=] [--pipeline=] [--name=] [--path=]
```

* **--provider** The application provider to use (currently, continuousphp is the only supported application provider)
* **--token** A valid token to consume the provider API (ie: continuousphp API token)
* **--repository-provider** The repository provider to use (ie: git-hub, bitbucket...)  
  *(for continuousphp only)*
* **--repository** The repository key to use (ie: continuousphp/deploy-agent)
* **--pipeline** The pipeline to use (ie: refs/heads/master)  
  *(for continuousphp only)*
* **--name** The name of the application
* **--path** The destination path of the application

### Token

The `token` depends on the Provider. At this time, the continousphp API is the only one supported.
You can retrieve your personal `token` on your Profile page at [continuousphp.com/profile](https://continuousphp.com/profile/)


## Deploy an application
```
./agent deploy application [--name=] [--build=]
```

* **--name** The name of the application
* **--build** The ID of the build to deploy

# Application path

When a deployment occurs, the Deploy Agent creates a new folder for every build in its dedicated workspace.
To enable the new build, it will create/update a symlink to the current build

```
[application-path]
        |
        +---[/current] (symlink to current build)
        |
        +---[/build-1]
        |
        +---[/build-2]
```

# Hooks

During the deployment workflow the Deploy Agent can execute project specific commands through hooks to define in a
continuous.yml file as following:

```yaml
# continuous.yml
deployment:
  hooks:
    AfterInstall:
      - command: echo 'the application is successfully installed'
    BeforeActivate:
      - command: echo 'the application is going to start'
    AfterActivate:
      - command: echo 'the application has started'
```

# Webhook setup

The URL of the hook to implement in [continuousphp](https://continuousphp.com) is `<baseuri>/webhook/continuousphp`.
Configure it as following:
![continuousphp setup](https://raw.githubusercontent.com/continuousphp/deploy-agent/master/resources/img/continuousphp-setup.png)

## Events

* **AfterInstall :** triggered just after the application package has been extracted
* **BeforeActivate :** triggered just before the symlink update
* **AfterActivate :** triggered just after the symlink update

# Contributing

Please note that this project is released with a [Contributor Code of Conduct](http://contributor-covenant.org/version/1/2/0/).
By participating in this project you agree to abide by its terms.

Feel free to fork the project, create a feature branch, and send us a pull request!
