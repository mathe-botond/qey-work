<?php
namespace qeyworktest;

/**
 * @author Dexx
 */
class UserTest extends \PHPUnit_Framework_TestCase {
    public function testSimple() {
        $user = new \qeywork\User(new \qeywork\Session('test'));
        $this->assertTrue(! $user->isLoggedIn());
        $this->assertTrue(! $user->isAdmin());
    }
}
