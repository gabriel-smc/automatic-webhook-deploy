<?php
/**
 * @module sample-config
 * @version 2018.10.21, 02:50
 *
 * Sample config file for bitbucket hooks.
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
    'gitCommand' => 'git',

    /** Folder containing all repositories, *REQUIRED* */
    'repositoriesPath' => '/path/to/repositories',

    /** creating folder mode, optional */
    'folderMode' => 0700,

    /** Enable logging, optional */
    'log' => true,

    /** Logging file name, optional */
    'logFile' => 'bitbucket.log',

    /** clear log each time, optional */
    'logClear' => true,

    /** show debug info in log, optional */
    'verbose' => true,

    /** Show payload contents in log, optional (ATTENTION: May be very expensive output!) */
    'logPayload' => false,

    /** Show debug info in log, optional */
    'logDebug' => false,

    // /** If specified then setup default php timezone (if server's PHP is misconfigured) */
    // 'setTimezone' => 'UTC',

);

/** List of deployed projects... */
$PROJECTS = array(

    /** The key is a bitbucket.org repository full name *REQUIRED* */
    'bitbucketUsername/repoName-1' => array(

        /** Branch name */
        'master' => array(

            /** Path to deploy project, *REQUIRED* */
            'deployPath'  => '/deploy_path',

            /** command to execute after deploy, optional */
            // 'postHookCmd' => 'your_command',

        ),

        /** Branch name */
        'featureBranch' => array(

            /** Path to deploy project, *REQUIRED* */
            'deployPath'  => '/feature_deploy_path',

            /** command to execute after deploy, optional */
            // 'postHookCmd' => 'your_command',

        ),

    ),

    /** The key is a bitbucket.org repository full name *REQUIRED* */
    'bitbucketUsername/repoName-N' => array(

        /** Branch name */
        'someBranchName' => array(

            /** Path to deploy project, *REQUIRED* */
            'deployPath'  => '/some_deploy_path',

            /** command to execute after deploy, optional */
            // 'postHookCmd' => 'your_command',

        ),

    ),

);
