<?php
namespace qeyworktest;
use qeywork as q;

include __DIR__ . '\\..\\boot.php';

class TestGlobals extends \qeywork\Globals {
    public function editValue($global, $key, $value) {
        $this->globals[$global][$key] = $value;
    }
}

function getTestGlobals($uri) {
    $globals = new TestGlobals();

    $server = [
        'SERVER_NAME' => 'localhost',
        'REQUEST_URI' => '/'
    ];
    $globals->swapSuperGlobalWith(q\Globals::KEY_SERVER, $server);

    $request = [
        '_target' => $uri
    ];
    $globals->swapSuperGlobalWith(q\Globals::KEY_REQUEST, $request);
    
    return $globals;
}

function getLocations() {
    
    $current = new q\Path(__DIR__);
    return new q\Locations(
    
        new q\Location(
            $current->parentDir(), new q\Url('http://localhost/') 
        ),
        new \qeywork\RelativePath('~qeyWork/tests/'),
        new \qeywork\RelativePath('~qeyWork')
    );
}