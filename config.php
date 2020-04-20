<?php
/**
 * @module sample-config
 * @version 2019.01.25, 22:07
 *
 * Sample config file for bitbucket/github hooks.
 *
 * Rename or copy it to `config.php`.
 *
 * Based on 'Automated git deployment' script by Jonathan Nicoal:
 * http://jonathannicol.com/blog/2013/11/19/automated-git-deployments-from-bitbucket/
 *
 * See README.md and config.sample.php
 *
 * ---
 * Igor Lilliputten
 * mailto: igor at lilliputten dot ru
 * http://lilliputten.ru/
 *
 * Ivan Pushkin
 * mailto: iv dot pushk at gmail dot com
 */

// Base tool configuration:
$CONFIG = array(

    /** Git command, *REQUIRED* */
    'gitCommand' => '/usr/bin/git',

    /** Absolute folder containing all repositories, *REQUIRED* */
    'repositoriesPath' => '/var/www/dev/repo',

    /** creating folder mode, optional */
    'folderMode' => 0755,

    /** Enable logging, optional */
    'log' => true,

    /** Logging file name, optional */
    'logFile' => 'github-hooks.log',

    /** clear log each time, optional */
    'logClear' => true,

    /** show extra info (eg config data) in log, optional */
    'verbose' => true,

    /** Show payload contents in log, optional (ATTENTION: May be very expensive output!) */
    'logPayload' => false,

    /** Show debug info in log, optional */
    'logDebug' => false,

    // /** If specified then setup default php timezone (if server's PHP is misconfigured) */
    'setTimezone' => 'Australia/Melbourne',

);

/** List of deployed projects... */
$PROJECTS = array(

    /** The key is a repository full name in form `owner/repository` *REQUIRED* */
    'dealer-solutions/sellmycar' => array(

        /** QA API on Develop branch */
        'develop' => array(
            /** Absolute path to deploy project, *REQUIRED* */
            'deployPath'  => '/var/www/qasellmy/deploy',

            /** command to execute after deploy, optional */
            'postHookCmd' => '/var/www/qasellmy/api.sh',
        ),

        /** QA Frontend on Develop branch */
        'develop-cms' => array(
            /** Absolute path to deploy project, *REQUIRED* */
            'deployPath'  => '/var/www/qasellmy/deploy/public',

            /** command to execute after deploy, optional */
            'postHookCmd' => '/var/www/qasellmy/cms.sh',
        ),

    ),
    /** Use the same for other repositories */
    /** The key is a repository full name in form `owner/repository` *REQUIRED* */
    // 'owner/repo-2' => array(

    //     /** Master branch */
    //     'someBranchName' => array(

    //         /** Absolute path to deploy project, *REQUIRED* */
    //         'deployPath'  => '/path/another_deploy_folder',

    //         /** command to execute after deploy, optional */
    //         // 'postHookCmd' => 'your_command',

    //     ),

    // ),

);
