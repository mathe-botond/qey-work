<?php
namespace qeywork;

/**
 * @author Dexx
 */
class UserModel extends Model implements IUserModel {
    /** @var Field */
    public $username;
    /** @var Field */
    public $password;
    
    public function __construct() {
        $this->password = new Field('password');
    }
}
