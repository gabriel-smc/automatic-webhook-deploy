<?php
/**
 * @module bitbucket
 * @version 2018.10.21, 02:50
 *
 * Routines for work with bitbucket server, repositories and projects.
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

/*{{{ *** Global variables */

define('DEFAULT_FOLDER_MODE', 0755);

if ( !defined('NL') ) {
    define('NL',"\n");
}

$PAYLOAD   = array ();
$BRANCHES  = array ();
$REPO      = ''; // full name
$REPO_NAME = ''; // name

/*}}}*/

function initConfig ()/*{{{ Initializing repo configs */
{
    global $CONFIG, $PROJECTS;

    $tmpProjects = array();

    // Bitbucket uses lower case repo names!
    $hadUppercaseKeys = false;
    foreach ( $PROJECTS as $repoName => $config ) {
        $tmpProjects[strtolower($repoName)] = $config;
        $hadUppercaseKeys = true;
    }

    // Rewrite projects list if has changes
    if ( $hadUppercaseKeys ) {
        $PROJECTS = $tmpProjects;
    }

    // Set default folder mode if absent
    if ( empty($CONFIG['folderMode']) ) {
        $CONFIG['folderMode'] = DEFAULT_FOLDER_MODE;
    }

    if ( $CONFIG['verbose'] ) {
        _LOG_VAR('CONFIG',$CONFIG);
    }

}/*}}}*/
function initLog ()/*{{{ Initializing log variables */
{
    _LOG_INIT();

}/*}}}*/
function initPayload ()/*{{{ Get posted data */
{
    global $CONFIG, $PAYLOAD;

    if (isset($_SERVER['HTTP_X_EVENT_KEY'], $_SERVER['HTTP_X_HOOK_UUID'], $_SERVER['HTTP_USER_AGENT'],
        $_SERVER['REMOTE_ADDR'])) {
        _LOG('*** ' . $_SERVER['HTTP_X_EVENT_KEY'] . ' #' . $_SERVER['HTTP_X_HOOK_UUID'] .
            ' (' . $_SERVER['HTTP_USER_AGENT'] . ')');
        _LOG('remote addr: ' . $_SERVER['REMOTE_ADDR']);
    } else {
        _LOG('*** [unknown http event key] #[unknown http hook uuid] (unknown http user agent)');
    }

    if ( isset($_POST['payload']) ) { // old method
        $PAYLOAD = $_POST['payload'];
    } else { // new method
        $PAYLOAD = json_decode(file_get_contents('php://input'));
    }

    if ( empty($PAYLOAD) ) {
        _ERROR("No payload data for checkout!");
        exit;
    }

    if ( !isset($PAYLOAD->repository->name, $PAYLOAD->push->changes) ) {
        _ERROR("Invalid payload data was received!");
        exit;
    }

    _LOG("Valid payload was received!");
    if ( $CONFIG['logPayload'] ) {
        _LOG_VAR('PAYLOAD',$PAYLOAD);
    }

}/*}}}*/
function fetchParams ()/*{{{ Get parameters from bitbucket payload now only (REPO) */
{
    global $CONFIG, $REPO, $REPO_NAME, $PAYLOAD, $PROJECTS, $BRANCHES;

    // Get repository name:
    $REPO = strtolower($PAYLOAD->repository->full_name);
    // _DEBUG_VAR('REPO', $REPO);
    if ( empty($PROJECTS[$REPO]) ) {
        _ERROR("Not found repository config for '$REPO'!");
        exit;
    }

    $REPO_NAME = strtolower($PAYLOAD->repository->name);
    _LOG_VAR('Repository name', $REPO_NAME);

    foreach ( $PAYLOAD->push->changes as $change ) {
        if ( is_object($change->new) && $change->new->type == "branch" ) {
            $branchName = $change->new->name;
            if ( isset($PROJECTS[$REPO][$branchName]) ) {
                // Create branch name for checkout
                array_push($BRANCHES, $branchName);
                _LOG("Changes in branch '".$branchName."' was fetched");
            }
        }
    }

    if ( empty($BRANCHES) ) {
        _ERROR("Nothing to update (no branches found)! Please check correct branch names in your config PROJECTS list.");
    }

}/*}}}*/
function checkPaths ()/*{{{ Check repository and project paths; create them if neccessary */
{
    global $REPO, $CONFIG, $PROJECTS, $BRANCHES;

    // Check for repositories folder path; create if absent
    $repoRoot = $CONFIG['repositoriesPath'];
    if ( !is_dir($repoRoot) ) {
        $mode = $CONFIG['folderMode'];
        if ( mkdir($repoRoot,$mode,true) ) {
            chmod($repoRoot,$mode); // NOTE: Ensuring folder mode!
            _LOG("Creating root repositories folder '".$repoRoot." (".decoct($mode).") for '$REPO'");
        }
        else {
            _ERROR("Error creating root repositories folder '".$repoRoot." for '$REPO'! Exiting.");
            exit;
        }
    }

    // Create folder if absent for each pushed branch
    foreach ( $BRANCHES as $branchName ) {
        $deployPath = $PROJECTS[$REPO][$branchName]['deployPath'];
        if ( !is_dir($deployPath) ) {
            $mode = $CONFIG['folderMode'];
            if ( mkdir($deployPath,$mode,true) ) {
                chmod($deployPath,$mode); // NOTE: Ensuring folder mode!
                _LOG("Creating project folder '".$deployPath.
                    " (".decoct($mode).") for '$REPO' branch '$branchName'");
            }
            else {
                _ERROR("Error creating project folder '".$deployPath.
                    "' for '$REPO' branch '$branchName'! Exiting.");
                exit;
            }
        }
    }

}/*}}}*/
function placeVerboseInfo ()/*{{{ Place verbose log information -- if specified in config */
{
    global $REPO, $REPO_NAME, $CONFIG, $BRANCHES;

    if ( $CONFIG['verbose'] ) {
        // _LOG_VAR('REPO',$REPO);
        _LOG_VAR('repoPath',$CONFIG['repositoriesPath'].DIRECTORY_SEPARATOR.$REPO_NAME.'.git');
        // _LOG_VAR('BRANCHES',$BRANCHES);
    }
}/*}}}*/
function fetchRepository ()/*{{{ Fetch or clone repository */
{
    global $REPO, $REPO_NAME, $CONFIG;

    // Compose current repository path
    $repoRoot = $CONFIG['repositoriesPath'];
    $repoPath = $repoRoot.DIRECTORY_SEPARATOR.$REPO_NAME.'.git';

    // If repository or repository folder are absent then clone full repository
    if ( !is_dir($repoPath) || !is_file($repoPath.DIRECTORY_SEPARATOR.'HEAD') ) {
        _LOG("Repository folder absent for '$REPO', cloning...");

        $cmd = 'cd "'.$repoRoot.'" && '.$CONFIG['gitCommand']
            .' clone --mirror git@bitbucket.org:'.$REPO.'.git "'.$REPO_NAME.'.git" 2>&1';
        _LOG_VAR('cmd',$cmd);
        // system($cmd, $status);
        exec($cmd, $output, $status);

        if ( $status !== 0 ) {
            _ERROR('Cannot clone repository git@bitbucket.org:'.$REPO.'.git: '.NL.implode(NL,$output));
            exit;
        }
    }
    // Else fetch changes
    else {
        _LOG("Repositury folder exists for '$REPO', fetching...");

        $cmd = 'cd "'.$repoPath.'" && '.$CONFIG['gitCommand'].' fetch 2>&1';
        _LOG_VAR('cmd',$cmd);
        // system($cmd, $status);
        exec($cmd, $output, $status);

        if ( $status !== 0 ) {
            _ERROR("Cannot fetch repository '$REPO' in '$repoPath': ".NL.implode(NL,$output));
            exit;
        }
    }

}/*}}}*/
function checkoutProject ()/*{{{ Checkout project into target folder */
{
    global $REPO, $REPO_NAME, $CONFIG, $PROJECTS, $BRANCHES;

    // Compose current repository path
    $repoPath = $CONFIG['repositoriesPath'].DIRECTORY_SEPARATOR.$REPO_NAME.'.git';

    // Checkout project files
    foreach ( $BRANCHES as $branchName ) {

        $deployPath = $PROJECTS[$REPO][$branchName]['deployPath'];

        $cmd = 'cd "'.$repoPath.'" && GIT_WORK_TREE="'.$deployPath.'" '.$CONFIG['gitCommand'].' checkout -f '.$branchName.' 2>&1';
        _LOG_VAR('cmd',$cmd);
        // system($cmd, $status);
        exec($cmd, $output, $status);

        if ( $status !== 0 ) {
            _ERROR("Cannot checkout branch '$branchName' in repo '$REPO': ".NL.implode(NL,$output));
            exit;
        }

        $postHookCmd = $PROJECTS[$REPO][$branchName]['postHookCmd'];
        if ( !empty($postHookCmd) ) {
            $cmd = 'cd "'.$deployPath.'" && '.$postHookCmd.' 2>&1';
            _LOG_VAR('cmd',$cmd);
            // system($cmd, $status);
            exec($cmd, $output, $status);

            if ( $status !== 0 ) {
                _ERROR("Error in post hook command for branch '$branchName' in repo '$REPO': ".NL.implode(NL,$output));
                exit;
            }
        }

        // Log the deployment
        // TODO: Catch output & errors (` 2>&1`)???
        $cmd = 'cd "'.$repoPath.'" && '.$CONFIG['gitCommand'].' rev-parse --short '.$branchName;
        _LOG_VAR('cmd',$cmd);
        $hash = rtrim(shell_exec($cmd));

        _LOG("Branch '$branchName' was deployed in '".$deployPath."', commit #$hash");
    }
}/*}}}*/
