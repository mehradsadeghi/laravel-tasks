<?php

class WelcomeTest extends TestCase
{
    /** @test */
    public function welcome_is_shown_correctly()
    {
        $this->get('/')->assertSee('Welcome');
    }

    /** @test */
    public function welcome_view_is_being_triggered_correctly()
    {
        $this->get('/')->assertViewIs('welcome');
    }
}
