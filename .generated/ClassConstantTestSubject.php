<?php
namespace apptest;
use qeywork as q;

class AnotherClassConstantTestSubject {}

class ClassConstantTestSubject {
    public function getAnotherClassName() {
        return 'AnotherClassConstantTestSubject';
    }
    
    public function getUrlClassName() {
        return '\\qeywork\\Url';
    }
    
    public function getUrlShortClassName() {
        return 'q\\Url';
    }
}
