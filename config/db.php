<?php

class Connection {

    private $host = "127.0.0.1:27017"; //* host:port
    private $userpass = "administrator:123123q"; // username:password , leave blank if none
    var $mongo;

    function __construct() {

        // connect to mongodb
        $client = new MongoDB\Client("mongodb://".$this->userpass."@".$this->host.""); // if authenticated
        // $this->mongo = new MongoDB\Client("mongodb://".$this->host.""); // if not authenticated
        $this->mongo = $client->test; //use database name at test
    }

    // function connect() {
    //     return shell_exec('mongo --host "159.203.113.11:27017" -u administrator -p 123123q --authenticationDatabase admin');
    // }

}


// test case ***Depricated***


// $con = new Connection;
// $filter = [];
// $options = [];
// $query = new MongoDB\Driver\Query($filter, $options);
// $rows = $con->mongo->executeQuery($con->dbname.'.yipee', $query); // $mongo contains the connection object to MongoDB

// foreach($rows as $r){
//     $a = (string)$r->_id; // convert object id to string
//     $r = json_decode(json_encode($r), true);
//     $r['_id'] = $a;
//     print_r(json_encode($r));
// }

?>
