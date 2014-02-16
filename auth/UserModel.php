<?php
namespace qeywork;

/**
 * @author Dexx
 */
class UserModel extends Model {
    /** @var Field */
    public $password;
    
    public function __construct() {
        $this->password = new Field('password');
    }
}

?>
