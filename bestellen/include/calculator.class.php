<?

class calculator {

    var $name;
    var $orderarticle;

    var $article_webspace;
    var $article_webspace_min;
    var $article_webspace_max;

    var $article_traffic;
    var $article_traffic_min;
    var $article_traffic_max;
    
    var $article_subdomain;
    var $article_subdomain_min;
    var $article_subdomain_max;

    var $article_mailaccount;
    var $article_mailaccount_min;
    var $article_mailaccount_max;

    var $article_mailforwarder;
    var $article_mailforwarder_min;
    var $article_mailforwarder_max;

    var $article_autoresponder;
    var $article_autoresponder_min;
    var $article_autoresponder_max;

    var $article_shellaccount;
    var $article_shellaccount_min;
    var $article_shellaccount_max;

    var $article_cronjob;
    var $article_cronjob_min;
    var $article_cronjob_max;

    var $article_mysql;
    var $article_mysql_min;
    var $article_mysql_max;

    var $article_ftp;
    var $article_ftp_min;
    var $article_ftp_max;

    function calculator($n) {
	$this->name = $n;
    }

    function set_article_webspace($articleid) {
	$this->article_webspace = $articleid;
    }

    function set_article_traffic($articleid) {
	$this->article_traffic = $articleid;
    }
    
    function set_article_subdomain($articleid) {
	$this->article_subdomain = $articleid;
    }

    function set_article_mailaccount($articleid) {
	$this->article_mailaccount = $articleid;
    }

    function set_article_mailforwarder($articleid) {
	$this->article_mailforwarder = $articleid;
    }

    function set_article_autoresponder($articleid) {
	$this->article_autoresponder = $articleid;
    }

    function set_article_shellaccount($articleid) {
	$this->article_shellaccount = $articleid;
    }

    function set_article_cronjob($articleid) {
	$this->article_cronjob = $articleid;
    }

    function set_article_mysql($articleid) {
	$this->article_mysql = $articleid;
    }

    function set_article_ftp($articleid) {
	$this->article_ftp = $articleid;
    }

    function set_webspace($min=false,$max=100) {
	$this->article_webspace_min = $min;
	$this->article_webspace_max = $max;
    }

    function set_traffic($min=false,$max=100) {
	$this->article_traffic_min = $min;
	$this->article_traffic_max = $max;
    }

    function set_subdomain($min=false,$max=100) {
	$this->article_subdomain_min = $min;
	$this->article_subdomain_max = $max;
    }

    function set_mailaccount($min=false,$max=100) {
	$this->article_mailaccount_min = $min;
	$this->article_mailaccount_max = $max;
    }

    function set_mailforwarder($min=false,$max=100) {
	$this->article_mailforwarder_min = $min;
	$this->article_mailforwarder_max = $max;
    }

    function set_autoresponder($min=false,$max=100) {
	$this->article_autoresponder_min = $min;
	$this->article_autoresponder_max = $max;
    }

    function set_shellaccount($min=false,$max=100) {
	$this->article_shellaccount_min = $min;
	$this->article_shellaccount_max = $max;
    }

    function set_cronjob($min=false,$max=100) {
	$this->article_cronjob_min = $min;
	$this->article_cronjob_max = $max;
    }

    function set_mysql($min=false,$max=100) {
	$this->article_mysql_min = $min;
	$this->article_mysql_max = $max;
    }

    function set_ftp($min=false,$max=100) {
	$this->article_ftp_min = $min;
	$this->article_ftp_max = $max;
    }

    function set_orderarticle($oa) {
	$this->orderarticle = $oa;
    }

    function getprice($id) {
	global $db;
	$res = $db->query("select * from biz_produkte where produktid='$id'");
	$row = $db->fetch_array($res);
	return $row[preis];
    }

    function getarticlename($id) {
	global $db;
	$res = $db->query("select * from biz_produkte where produktid='$id'");
	$row = $db->fetch_array($res);
	return $row[bezeichnung];
    }



}


?>