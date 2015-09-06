<?php

/*{{{ v.150906.001 (0.0.1)

	Routines for work with bitbucket server, repositories and projects.

	Based on 'Automated git deployment' script by Jonathan Nicoal:
	http://jonathannicol.com/blog/2013/11/19/automated-git-deployments-from-bitbucket/

	See README.md and CONFIG.php

	---
	Igor Lilliputten
	mailto: igor at lilliputten dot ru
	http://lilliputtem.ru/

}}}*/

/*{{{ *** Global variables */

define('DEFAULT_BRANCH', 'master');
define('DEFAULT_FOLDER_MODE', 0755);

$REPO = '';
$PAYLOAD = array ();

/*}}}*/

// Initalize log variables
function initLog ()/*{{{*/
{
	global $CONFIG;

	if ( !empty($CONFIG['log']) ) {
		$GLOBALS['_LOG_ENABLED'] = true;
	}
	if ( !empty($CONFIG['logFile']) ) {
		$GLOBALS['_LOG_FILE'] = $CONFIG['logFile'];
	}
	if ( !empty($CONFIG['logClear']) ) {
		_LOG_CLEAR();
	}

}/*}}}*/

// Get posted data
function initPayload ()/*{{{*/
{
	global $PAYLOAD, $CONFIG, $PROJECTS;

	if ( isset($_POST['payload']) ) { // old method
		$PAYLOAD = $_POST['payload'];
	} else { // new method
		$PAYLOAD = json_decode(file_get_contents('php://input'));
	}

	if ( empty($PAYLOAD) ) {
		_ERROR("No payload data for checkout!");
		exit;
	}

}/*}}}*/

// Get parameters from bitbucket payload (REPO)
function fetchParams ()/*{{{*/
{
	global $REPO, $PAYLOAD, $CONFIG, $PROJECTS;

	$REPO = $PAYLOAD->repository->name;
	if ( empty($PROJECTS[$REPO]) ) {
		_ERROR("Not found repository config for '$REPO'!");
		exit;
	}

}/*}}}*/

// Check repository and project paths; create them if neccessary
function checkPaths ()/*{{{*/
{
	global $REPO, $CONFIG, $PROJECTS;

	if ( !is_dir($CONFIG['repositoriesPath']) ) {
		$mode = ( !empty($CONFIG['folderMode']) ) ? $CONFIG['folderMode'] : DEFAULT_FOLDER_MODE;
		if ( mkdir($CONFIG['repositoriesPath'],$mode,true) ) {
			_LOG("Creating repository folder '".$CONFIG['repositoriesPath']." (".decoct($mode).") for '$REPO'");
		}
		else {
			_ERROR("Error creating repository folder '".$CONFIG['repositoriesPath']." for '$REPO'! Exiting.");
			exit;
		}
	}

	if ( !is_dir($PROJECTS[$REPO]['projPath']) ) {
		$mode = ( !empty($CONFIG['folderMode']) ) ? $CONFIG['folderMode'] : DEFAULT_FOLDER_MODE;
		if ( mkdir($PROJECTS[$REPO]['projPath'],$mode,true) ) {
			_LOG("Creating project folder '".$PROJECTS[$REPO]['projPath']." (".decoct($mode).") for '$REPO'");
		}
		else {
			_ERROR("Error creating project folder '".$PROJECTS[$REPO]['projPath']." for '$REPO'! Exiting.");
			exit;
		}
	}

	// if ( !is_dir($PROJECTS[$REPO]['projPath']) ) {
	// 	_ERROR("Invalid project path '".$PROJECTS[$REPO]['projPath']."' for repository '$REPO'!");
	// 	exit;
	// }

}/*}}}*/

// Place verbose log information if specified in config
function placeVerboseInfo ()/*{{{*/
{
	global $REPO, $CONFIG, $PROJECTS;

	$repoPath = $CONFIG['repositoriesPath'].$REPO.'.git/';

	if ( $CONFIG['verbose'] ) {
		_LOG_VAR('CONFIG: ',$CONFIG);
		_LOG_VAR('REPO: ',$REPO);
		_LOG_VAR('repoPath: ',$repoPath);
		_LOG_VAR('$PROJECTS[$REPO]: ',$PROJECTS[$REPO]);
	}
}/*}}}*/

// Fetch or clone repository
function fetchRepository ()/*{{{*/
{
	global $REPO, $CONFIG, $PROJECTS;

	$repoPath = $CONFIG['repositoriesPath'].$REPO.'.git/';

	if ( !is_dir($repoPath) || !is_file($repoPath.'HEAD') ) {
		_LOG("Absent repository for '$REPO', cloning");
		exec('cd '.$CONFIG['repositoriesPath'].' && '.$CONFIG['gitCommand'].' clone --mirror git@bitbucket.org:'.$CONFIG['bitbucketUsername'].'/'.$REPO.'.git');
	}
	else {
		_LOG("Fetching repository '$REPO'");
		exec('cd '.$repoPath.' && '.$CONFIG['gitCommand'].' fetch');
	}

}/*}}}*/

// Checkout project into target folder
function checkoutProject ()/*{{{*/
{
	global $REPO, $CONFIG, $PROJECTS;

	$repoPath = $CONFIG['repositoriesPath'].$REPO.'.git/';

	// Checkout
	$branch = ( !empty($PROJECTS[$REPO]['branch']) ? $PROJECTS[$REPO]['branch']: DEFAULT_BRANCH;
	exec('cd '.$repoPath.' && GIT_WORK_TREE='.$PROJECTS[$REPO]['projPath'].' '.$CONFIG['gitCommand'].' checkout -f '.$branch);

	// Log the deployment
	$hash = rtrim( shell_exec('cd '.$repoPath.' && '.$CONFIG['gitCommand'].' rev-parse --short HEAD') );
	_LOG("Deployed repository '".$REPO."', commit '".$hash."'");

}/*}}}*/

