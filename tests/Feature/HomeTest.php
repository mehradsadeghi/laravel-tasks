<?php

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function home_wont_be_shown_to_guest_users()
    {
        $this->get('/home')->assertRedirect('/login');
    }

    /** @test */
    public function home_view_is_being_triggered_correctly()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/home')->assertViewIs('home');
    }
}
