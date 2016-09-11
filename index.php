<?php

// Constants
$sid_url = "http://www.nb.admin.ch/dienstleistungen/swissinfodesk/index.html?lang=de";
$admin_email = "micha.rieser@bar.admin.ch";

// Get input
$article_title  = $_GET['title'];
$wikipedia_lang = $_GET['lang'];
$back_url       = $_GET['back'];

// Check input
if (!$article_title || !$wikipedia_lang || !$back_url) {
  echo "Too few info, please et 'title', 'lang' and 'back'.";
  exit;
}

// Prepare SID request values
$sid_question = "This is an automated message from Wikipedia.\nA question has been asked about article \"$article_title\".\nHave a look:\nhttps://$wikipedia_lang.wikipedia.org/wiki/Talk:$article_title.";
$sid_subject = "Wikipedia notification about \"$article_title\"";
$sid_name = "Wikipedia automated notification";
$sid_lang = $wikipedia_lang;
$sid_email = $admin_email;

// Send SID request
$data = array('frage_x_not_empty' => $sid_question, 'frage_x_not_empty' => $sid_subject, 
	      'sprache_x_not_empty' => $sid_lang, 'email_x_not_empty' => $sid_email, '_x_no_check' => 'Senden',
	      'name_x_nocheck' => $sid_name, 'flexindex' => '0_13');
$options = array(
		 'http' => array(
				 'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				 'method'  => 'POST',
				 'content' => http_build_query($data)
				 )
		 );
$context = stream_context_create($options);
$result  = file_get_contents($sid_url, false, $context);

// Redirect to the back URL
if ($result === FALSE) {
  echo "Unable to send to request";
} else {
  header("Location: $back_url");
}
exit;
?>