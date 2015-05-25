<?php
namespace qeywork;

/**
 * @author Dexx
 */
class UserEntity extends Entity implements IUserEntity {
    /** @var Field */
    public $username;
    /** @var Field */
    public $password;
    
    public function __construct() {
        $this->password = new Field('password');
    }
}
