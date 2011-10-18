<?php
/**
 *
 * index - default file used to drive web site
 *
 * Author: Tamara Temple <tamara@tamaratemple.com>
 * Created: 2011/10/18
 * Copyright (c) 2011 Tamara Temple Web Development
 * License: GPLv3
 *
 */

include("lib/rssreader.php");
include("lib/class.Debug.php");
$dbg = new Debug(FALSE);
if ($dbg->is_on()) {
  error_reporting(-1);
  ini_set("display_errors","on");
  ini_set("display_startup_errors","on");
  ini_set("html_errors","on");
}

include("lib/smarty/libs/Smarty.class.php");
$smarty = new Smarty;
$smarty->caching = FALSE;

$MyShows = array("House", "Leverage", "Doctor Who", "Hawaii Five 0", "Breaking Bad", "Burn Notice", "The Big Bang Theory", "The Good Wife", "The Simpsons", "Warehouse 13", "Torchwood", "Suburgatory", "The Walking Dead", "Dexter", "Top Gear", "How To Be A Gentleman", "Mythbusters", "Fringe", "Person of Interest", "A Gifted Man", "Castle", "The Sarah Jane Adventures");


$MyFeeds = array('kattv'=>"http://www.kat.ph/tv/?rss=1",
		 'demonoidtv'=>'http://static.demonoid.me/rss/3.xml',
		 'ezrss.it'=>'http://ezrss.it/search/index.php?mode=rss');

$goodstories = array();

foreach ($MyFeeds as $feed => $url) {
  $dbg->p(": starting feed: ",$url,__FILE__,__LINE__);
  $rss = new rssFeed($url);
  if ($rss->error) die("RSS Error: $rss->error");
  $rss->parse();
  $goodstories = array_merge($goodstories, process_stories($rss->stories));
  $dbg->p(": goodstories: ",$goodstories,__FILE__,__LINE__);
  if ($dbg->is_on()) echo "---------------\n\n";
  
}

if ($dbg->is_on()) echo "****************** END OF PROCESSING ******************\n\n\n";

header("Content-type: application/rss+xml");

$smarty->assign("stories",$goodstories);
$smarty->display("rss.tpl");


/**
 * Process the stories in the rss feed
 *
 * @param array $stories -- the list of stories parsed from the rss feed
 * @return array - stories which match $MyShows
 * @author Tamara Temple <tamara@tamaratemple.com>
 **/
function process_stories($stories)
{
  global $dbg;
  $matches=array();
  foreach ($stories as $story) {
    $dbg->p(": story: ",$story,__FILE__,__LINE__);
    if (isset($story->title) && checkmatch($story->title)) {
      $dbg->p(": GOT A MATCH!!!!!!",NULL,__FILE__,__LINE__);
      $story_a = get_object_vars($story);
      $dbg->p(": story as object: ",$story_a,__FILE__,__LINE__);
      array_push($matches,$story_a);    
    }
  }
  return $matches;
}

/**
 * check if a title matches one of the contents of MyShows
 *
 * @param string $t - title of show
 * @return boolean
 * @author Tamara Temple <tamara@tamaratemple.com>
 **/
function checkmatch($t)
{
  global $MyShows, $dbg;
  
  $dbg->p(": t=",$t,__FILE__,__LINE__.' in '.__FUNCTION__.' ');
  
  foreach ($MyShows as $showname) {
    if (preg_match('/'.$showname.'/', $t)) {
	return TRUE;
    }
  }
  return FALSE;
}




