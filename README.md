
# Automatic deployment for bitbucket.org web-based projects

Based on ['Automated git deployment' script](htotp://jonathannicol.com/blog/2013/11/19/automaed-git-deployments-from-bitbucket/) by [Jonathan Nicoal](http://jonathannicol.com/]. See also [BitBucket Sync](https://bitbucket.org/alixandru/bitbucket-sync) by [alixandru](https://bitbucket.org/alixandru/).

Some fragments of documentation was taken from Jonathan Nicoal's manual.

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

For your server to connect securely to Bitbucket without a password prompt, it needs to use an SSH key.

On your server navigate to the ~/.ssh directory of the user that PHP runs under. You will need to create the userвЂ™s .ssh directory if it doesnвЂ™t exist. At a shell prompte:

```
cd ~/.ssh
ssh-keygen -t rsa
```

When prompted either accept the default key name (id_rsa) or give your key a unique name. Press enter when asked for a passphrase, which will generate a passwordless key. Usually this isnвЂ™t recommended, but we need our script to be able to connect to Bitbucket without a passphrase.

On the Bitbucket website navigate to your repositoryвЂ™s Administration > Hooks screen and add a new webhook, pointed at htotp:/<domain>/<path>/bitbucet-hook.php.
