<?php

/*{{{ v.151005.001 (0.0.2)

	Sample config file for bitbucket hooks.

	Based on 'Automated git deployment' script by Jonathan Nicoal:
	http://jonathannicol.com/blog/2013/11/19/automated-git-deployments-from-bitbucket/

	See README.md and config.sample.php

	---
	Igor Lilliputten
	mailto: igor at lilliputten dot ru
	http://lilliputtem.ru/

	Ivan Pushkin
	mailto: iv dot pushk at gmail dot com

}}}*/

/*{{{ Auxiliary variables, used only for constructing $CONFIG and $PROJECTS  */

$HOME_PATH         = '/home/g/goldenjeru/golden/';
$REPOSITORIES_PATH = $HOME_PATH.'.repositories/';
$PROJECTS_PATH     = $HOME_PATH;

/*}}}*/

// Base tool configuration:
$CONFIG = array(
	'bitbucketUsername' => 'lilliputten', // The username or team name where the
	// repository is located on bitbucket.org, *REQUIRED*

	'gitCommand' => 'git',                    // Git command, *REQUIRED*
	'repositoriesPath' => $REPOSITORIES_PATH, // Folder containing all repositories, *REQUIRED*
	'log' => true,                            // Enable logging, optional
	'logFile' => 'bitbucket.log',             // Logging file name, optional
	'logClear' => true,                       // clear log each time, optional
	'verbose' => true,                        // show debug info in log, optional
	'folderMode' => 0700,                     // creating folder mode, optional
);

// List of deploying projects:
$PROJECTS = array(
	'repo-name-1' => array( // The key is a bitbucket.org repository name
		'branch' => array(
			'deployPath' => $PROJECTS_PATH.'deploy_path/', // Path to deploy project, *REQUIRED*
			'postHookCmd' => 'your_command',               // command to execute after deploy, optional
		),
	),

	'repo-name-N' => array( // The key is a bitbucket.org repository name
		'branch' => array(
			'deployPath' => $PROJECTS_PATH.'deploy_path/', // Path to deploy project, *REQUIRED*
			'postHookCmd' => 'your_command',               // command to execute after deploy, optional
		),
	),
);
