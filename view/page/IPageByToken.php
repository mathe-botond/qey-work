<?php
namespace QeyWork\View\Page;

/**
 * @author Dexx
 */
interface IPageByToken extends IPage {
    public function getToken();
    public function setToken($token);
}
