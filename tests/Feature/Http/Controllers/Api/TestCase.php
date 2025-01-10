<?php

namespace Tests\Feature\Http\Controllers\Api;

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
