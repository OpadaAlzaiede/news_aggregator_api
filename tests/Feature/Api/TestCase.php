<?php

namespace Tests\Feature\Api;

abstract class TestCase extends \Tests\TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setup(): void {

        parent::setup();

        $this->withHeaders([
            'Accept' => 'application/json'
        ]);
    }
}
