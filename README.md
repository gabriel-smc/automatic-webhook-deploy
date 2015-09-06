
# Automatic deployment for bitbucket.org web-based projects

Based on ['Automated git deployment' script](http://jonathannicol.com/blog/2013/11/19/automated-git-deployments-from-bitbucket/) by Jonathan Nicoal.

Version 0.0.1
Last changes 2015.09.06

Documentation is in progress.

## Changes:

- Fixed new bitbucket.org webhooks interface (stream instead of POST). See [discussion](https://bitbucket.org/alixandru/bitbucket-sync/issues/34/bitbucket-api-change-breaks-gatewayphp#comment-None) on another synchronizator _bitbucket-sync_ by [alexandru](http://bitbucket.org/alixandru/) .
- Added support for multiple projects. See array `$PROJECTS` in **CONFIG.php**.

