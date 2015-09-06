
# Automatic deployment for bitbucket.org web-based projects

Based on ['Automated git deployment' script](http://jonathannicol.com/blog/2013/11/19/automated-git-deployments-from-bitbucket/) by Jonathan Nicoal. See also [BitBucket Sync](https://bitbucket.org/alixandru/bitbucket-sync) by [alixandru](https://bitbucket.org/alixandru/).

Version 0.0.1
Last changes 2015.09.06

Documentation is in progress.

## Changes/features

- Fixed new bitbucket.org webhooks interface (stream instead of POST). See [discussion](https://bitbucket.org/alixandru/bitbucket-sync/issues/34/bitbucket-api-change-breaks-gatewayphp#comment-None) on another synchronizator _bitbucket-sync_ by [alixandru](https://bitbucket.org/alixandru/).
- Added support for multiple projects. See array `$PROJECTS` in **CONFIG.php**.
- Optional fetching or cloning repositiories demand on their presence.
- Project and repository folders automaticly creating if they're not exists. (You need no to create empty folders before operations and can to reset and initiate full reload by simply removing entire repository/project folders.)

## Requirements

- PHP 5.3+;
- Git installed;
- Shell access;
- PHP exec function;
- SSH key pair for bitbucket created with **empty** passphrase;

## Installation

On the Bitbucket website navigate to your repositoryвЂ™s Administration > Hooks screen and add a new webhook, pointed at http:/<domain>/<path>/bitbucket-hook.php.
