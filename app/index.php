<?php 
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

//autoloader
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'classes/Instagram.php';
require_once 'classes/InstagramAuth.php';
require_once 'classes/InstagramActions.php';

$arguments = getopt('', [
	'username:',
	'password:',
	'mediaId::',
	'userId::',
	'commentText::',
	'setLike::',
	'setFollow::',
	'unsetFollow::',
	'setComment::'
]);

/**
* Authorize first
*/
if(!isset($arguments['username']) || !isset($arguments['password']))
{
	throw new Exception('--username and --password parameters are obligatory');
}

$instagramAuth = new InstagramAuth($arguments['username'], $arguments['password']);

if(!$instagramAuth->check())
{
	throw new Exception('User not logged in');
}
$instagramActions = new InstagramActions($instagramAuth);

/**
* Set like
*/
if(isset($arguments['setLike']) && isset($arguments['mediaId']))
{
	$action = $instagramActions->setLike($arguments['mediaId']);
	echo json_encode([
		'action' => 'setLike', 'argument' => $arguments['mediaId'], 'status' => $action
	]);
}

/**
* Set comment
*/
else if(isset($arguments['setComment']) && isset($arguments['mediaId']) && isset($arguments['commentText']))
{
	$action = $instagramActions->setComment($arguments['mediaId'], $arguments['commentText']);
	echo json_encode([
		'action' => 'setComment', 'argument' => $arguments['mediaId'], 'status' => $action
	]);
}

/**
* Set follow
*/
else if(isset($arguments['setFollow']) && isset($arguments['userId']))
{
	$action = $instagramActions->setFollow($arguments['userId']);
	echo json_encode([
		'action' => 'setFollow', 'argument' => $arguments['userId'], 'status' => $action
	]);
}

/**
* Unset follow
*/
else if(isset($arguments['unsetFollow']) && isset($arguments['userId']))
{
	$action = $instagramActions->unsetFollow($arguments['userId']);
	echo json_encode([
		'action' => 'unsetFollow', 'argument' => $arguments['userId'], 'status' => $action
	]);	
}

else
{
	echo json_encode(['status' => true]);
}
