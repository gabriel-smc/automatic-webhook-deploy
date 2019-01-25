# Changelog

## v.0.3.0 (2019.01.25)

- Added github support.
- Respository folders creating as `{REPO_TYPE}-{OWNER}-{REPO_NAME}.git` (in
  folder specified by `$CONFIG['repositoriesPath']` -- see config). `REPO_TYPE`
  can be 'bitbucket' or 'github'.
- `bitbucket-hook.php` renamed to `webhook.php`. Created `bitbucket-hook.php`
  stub (just includes `webhook.php`) for compatibility with previous versions.
- Github repository name changed to `automatic-webhook-deploy`.

## v.0.2.1 (2018.10.23)

- Using config repository key (in form `username/repo-name`) as repository
  folder name (under `repositoriesPath`). Thus, we have a two-level structure
  of repository folders storage.

## v.0.1.1 (2018.10.21)

- Escaping potential spaces in file names.
- Added config option `setTimezone` for misconfigured php server.
- Verbose exec errors (show error messages in log).
- Ensuring folder modes after `mkdir` (calling `chmod`). In some cases mkdir
  makes default modes.
- More informative log messages.
- New config parameters: `logDebug`, `logPayload`.

## v.0.0.2 (2015.10.05)

- Added project parameter for post deploy execution:
  `$PROJECTS['repo-name']['postHookCmd']` (see _config.sample.php_). For
  example, touch _index.wsgi_ for django configuration reloading: `...
  'postHookCmd' => 'touch index.wsgi', ...`. Command running in project folder
  (specified by `deployPath` parameter.

