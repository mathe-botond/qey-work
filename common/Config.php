<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 5/25/2015
 * Time: 10:44 PM
 */

namespace qeywork;


class Config {
    /** @var Locations */
    private $loc;
    private $index;
    private $dbConfig;
    private $mailConfig;
    private $title;
    private $appName;

    public function __construct(Locations $loc, $appName, IndexToken $index) {
        $this->loc = $loc;
        $this->index = $index;
        $this->mailConfig = new MailerConfigurator();
        $this->appName = $appName;
    }

    public function setDbConfig(DBConfig $dbConfig) {
        $this->dbConfig = $dbConfig;
    }

    public function setMailerConfig(MailerConfigurator $mailConfig) {
        $this->mailConfig = $mailConfig;
    }

    public function getDbConfig() {
        return $this->dbConfig;
    }

    public function getMailerConfig() {
        return $this->mailConfig;
    }

    public function getLocations() {
        return $this->loc;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getIndex() {
        return $this->index;
    }

    public function getAppName() {
        return $this->appName;
    }
}