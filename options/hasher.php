<?php

class hashGenerator {

    function randomHash() {
        $rnd = mt_rand();
        $hash = sha1(md5($rnd));
        return $hash;
    }
}

?>