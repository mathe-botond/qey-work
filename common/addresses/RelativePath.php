<?php
namespace qeywork;

/**
 * A relative path usable for Url and Paths
 *
 * @author Dexx
 */
class RelativePath extends Path {
    public function __construct($dirs, $file = "") {
        if (is_string($dirs)) {
            $dirs = trim($dirs, "/\\");
        }
        
        parent::__construct($dirs, $file);
    }
}
