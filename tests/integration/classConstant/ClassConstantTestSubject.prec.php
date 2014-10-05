<?php
namespace apptest;
use qeywork as q;

class AnotherClassConstantTestSubject {}

class ClassConstantTestSubject {
    public function getAnotherClassName() {
        return AnotherClassConstantTestSubject::class;
    }
    
    public function getUrlClassName() {
        return \qeywork\Url::class;
    }
    
    public function getUrlShortClassName() {
        return q\Url::class;
    }
}
