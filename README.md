deploy-agent
============
The Deploy Agent application enables developers and administrators to use [Continuous PHP](continuousphp.com) deploy services in their infrastructure,
You can get started in minutes by installing this Agent through Composer in your server infrastructure.

# Requirements
==

* Composer
* Phing
* The ability to run php from the commandline exec
* The ability to create symlink

# Features
==

* Connect to Continuous Php server
* Download and extract tarball
* Use symlinks or copy files
* Compatible with Windows Environment
* Use the standard Phing

# Installation
==

    ## Install the deploy agent with composer

        ./composer.phar up

    ## Allow symlink creation

        Add this line in your vhost:

        Options +FollowSymLinks +SymLinksIfOwnerMatch

    ## Install database

        Set rights on db file :
        chmod 755 data/db/deploy.sqlite

        Launch the doctrine command:
        php vendor/doctrine/doctrine-module/bin/doctrine-module orm:schema-tool:update --dump-sql

