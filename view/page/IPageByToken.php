<?php
namespace qeywork;

/**
 * @author Dexx
 */
interface IPageByToken extends IPage {
    public function getToken();
    public function setToken($token);
}
