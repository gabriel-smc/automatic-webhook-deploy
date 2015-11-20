<?php

/*{{{ v.151005.001 (0.0.2)

	Sample config file for bitbucket hooks.

	Based on 'Automated git deployment' script by Jonathan Nicoal:
	http://jonathannicol.com/blog/2013/11/19/automated-git-deployments-from-bitbucket/

	See README.md and CONFIG.php

	---
	Igor Lilliputten
	mailto: igor at lilliputten dot ru
	http://lilliputtem.ru/

}}}*/

/*{{{ Auxiliary variables, used only for constructing $CONFIG and $PROJECTS  */

$HOME_PATH = '/home/g/goldenjeru/golden/';
$REPOSITORIES_PATH = $HOME_PATH.'.repositories/';
$PROJECTS_PATH = $HOME_PATH;

/*}}}*/

// Base tool configuration:
$CONFIG = array(
	'bitbucketUsername' => 'lilliputten', // User name on bitbucket.org, *REQUIRED*
	'gitCommand' => 'git', // Git command, *REQUIRED*
	'repositoriesPath' => $REPOSITORIES_PATH, // Folder containing all repositories, *REQUIRED*
	'log' => true, // Enable logging, optional
	'logFile' => 'bitbucket.log', // Logging file name, optional
	// 'logClear' => true, // clear log each time, optional
	// 'verbose' => true, // show debug info in log, optional
	// 'folderMode' => 0700, // creating folder mode, optional
);

// List of deploying projects:
$PROJECTS = array(
	// 'repo-name' => array( // The key is a bitbucket.org repository name
	// 	'projPath' => $PROJECTS_PATH.'deploy_path/', // Path to deploy project, *REQUIRED*
	// 	// 'postHookCmd' => 'your_command', // command to execute after deploy, optional
	// 	// 'branch' => 'master', // Deploing branch, optional
	// ),
	'golden-site' => array(
		'branch' => 'work',
		'projPath' => $PROJECTS_PATH.'work/',
		// 'projPath' => $PROJECTS_PATH.'site/',
		'postHookCmd' => 'touch index.wsgi',
	),
	'golden-shop' => array(
		// 'branch' => 'work',
		// 'projPath' => $PROJECTS_PATH.'work/',
		'projPath' => $PROJECTS_PATH.'shop/',
		'postHookCmd' => 'touch index.wsgi',
	),
	'gold-master' => array(
		'projPath' => $PROJECTS_PATH.'old-site/',
	),
);


