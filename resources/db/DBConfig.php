<?php
namespace qeywork;

/**
 * @author Dexx
 */
class DBConfig {
    public $protocol;
    public $host;
    public $dbName;
    public $user;
    public $password;
    
    public function __construct(
            $protocol = '',
            $host = '',
            $dbName = '',
            $user = '',
            $password = '') {
        $this->protocol = $protocol;
        $this->host = $host;
        $this->dbName = $dbName;
        $this->user = $user;
        $this->password = $password;
    }
}
