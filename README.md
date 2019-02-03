
# Automatic deployment for bitbucket/github web-based projects

Based on [«Automated git deployment» script](http://jonathannicol.com/blog/2013/11/19/automated-git-deployments-from-bitbucket/) by [Jonathan Nicoal](http://jonathannicol.com/). See also [BitBucket Sync](https://bitbucket.org/alixandru/bitbucket-sync) by [alixandru](https://bitbucket.org/alixandru/).

Some fragments of this manual was taken from Jonathan Nicoal's documentation page.

- Version: 0.3.0
- Last changes: 2019.01.25
- See [Changelog](CHANGELOG.md)

Documentation is in progress.

## Features

- Fixed new bitbucket.org webhooks interface (stream instead of POST). See [discussion](https://bitbucket.org/alixandru/bitbucket-sync/issues/34/bitbucket-api-change-breaks-gatewayphp) on another synchronizator _bitbucket-sync_ by [alixandru](https://bitbucket.org/alixandru/).
- Added support for multiple projects. See array `$PROJECTS` in **config.sample.php**.
- Optional fetching or cloning repositiories demand on their presence.
- Project and repository folders automaticly creating if they're not exists. (You need no to create empty folders before operations and can to reset and initiate full reload by simply removing entire repository/project folders.)
- Post hook command execution.

## Requirements

- PHP 5.3+;
- Git installed;
- Shell access;
- PHP exec function;
- SSH key pair for bitbucket/github created with **empty** passphrase;

## Installation

For your server to connect securely to Bitbucket without a password prompt, it needs to use an SSH key.

On your server navigate to the **~/.ssh** directory of the user that PHP runs under. You will need to create the user's .ssh directory if it doesn't exist. At a shell prompt type:

```
cd ~/.ssh
ssh-keygen -t rsa
```

When prompted either accept the default key name (**id_rsa**) or give your key a unique name. Press enter when asked for a passphrase, which will generate a passwordless key. Usually this isn't recommended, but we need our script to be able to connect to Bitbucket without a passphrase.

A public and private key pair will be generated. Copy your public key — the one with a _.pub_ extension — to the clipboard. On the Bitbucket website navigate to _Account > SSH Keys_, and choose to add a new key. Paste in your public key and save it.

Back on your server, edit your **~/.ssh/config** file to add _bitbucket.org_ or _github.com_ as a host. This ensures that the correct key is used when connecting by SSH to target host. You'll need to create the config file if it doesn't exist:

```
Host github.com
    IdentityFile ~/.ssh/<your_private_key_file>
```

Whenever you do a git fetch host will verify your identity automatically, without prompting you for a password.

On the git server site navigate to your repository's _Administration > Webhooks_ screen and add a new webhook, pointed to `http://<domain>/<path>/webhook.php`.

For the github repository, you must specify the `application/json` value for the `Content-type` parameter.

## See also

- [Original Jonathan Nicoal's page](http://jonathannicol.com/blog/2013/11/19/automated-git-deployments-from-bitbucket/)
- [Build, test, and deploy with Pipelines - Atlassian Documentation](https://confluence.atlassian.com/bitbucket/bitbucket-pipelines-792496469.html)
