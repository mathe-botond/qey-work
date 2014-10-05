<?php
namespace apptest;

class AnotherClassConstantTestSubject {}

class ClassConstantTestSubject {
    public function getAnotherClassName() {
        return 'AnotherClassConstantTestSubject';
    }
    
    public function getUrlClassName() {
        return '\\qeywork\\Url';
    }
}
