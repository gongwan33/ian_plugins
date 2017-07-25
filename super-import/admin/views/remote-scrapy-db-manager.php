<?php
define('RMT_USR', 'root');
define('RMT_PWD', DB_PASSWORD);
define('RMT_HOST', '127.0.0.1');

class ScrapyDB {
    static private $rmdb = null;
    function __construct($dbname) {
        if($this->rmdb == null) {
            $this->rmdb = new wpdb(RMT_USR, RMT_PWD, $dbname, RMT_HOST);
        }
    }
    
    function getInstance() {
        return $this->rmdb;
    }

    function __destruct() {
        $this->rmdb->close();
    }
}
