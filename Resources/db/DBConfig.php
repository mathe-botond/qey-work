<?php
namespace QeyWork\Resources\Db;

/**
 * @author Dexx
 */
class DBConfig {
    const PROTOCOL_MYSQL = 'mysql';
    const HOST_LOCALHOST = 'localhost';
    const USER_ROOT = 'root';
    
    public $protocol;
    public $host;
    public $dbName;
    public $user;
    public $password;
    
    public function __construct(
            $protocol = self::PROTOCOL_MYSQL,
            $host = self::HOST_LOCALHOST,
            $dbName = '',
            $user = self::USER_ROOT,
            $password = '') {
        $this->protocol = $protocol;
        $this->host = $host;
        $this->dbName = $dbName;
        $this->user = $user;
        $this->password = $password;
    }
}
