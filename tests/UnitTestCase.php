<?php

/**
 * Base Test Case
 */
class UnitTestCase extends PHPUnit_Framework_TestCase {

    /**
     * Tear Down
     */
    public function tearDown() {
        parent::tearDown();
        Mockery::close();
    }
    
}