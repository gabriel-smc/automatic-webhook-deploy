# Catched Bugs (for R&D)

## 2018.10.21, 02:42 -- Apache can't to create `.ssh` folder.

```log
Cloning into bare repository 'axiomica-questionnaire-v1-1810-bundle.git'...
Could not create directory '/usr/share/httpd/.ssh'.
Host key verification failed.
fatal: Could not read from remote repository.

Please make sure you have the correct access rights
and the repository exists.
```

- [Could not create directory '/var/www/.ssh' - error message after pushing an existing repo · Issue #170 · ericpaulbishop/redmine_git_hosting](https://github.com/ericpaulbishop/redmine_git_hosting/issues/170)
- [php - Generating SSH keys for 'apache' user - Stack Overflow](https://stackoverflow.com/questions/7306990/generating-ssh-keys-for-apache-user)

