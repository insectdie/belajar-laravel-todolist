<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testLoginPage()
    {
        $this->get('/login')->assertSeeText("Login");
    }

    public function testLoginPageForMember()
    {
        $this->session([
            "user" => "andry"
        ])->get('/login')->assertRedirect("/");
    }

    public function testLoginSuccess()
    {
        $this->post('/login', [
            "user" => "andry",
            "password" => "rahasia"
        ])->assertRedirect("/")->assertSessionHas("user", "andry");
    }

    public function testLoginForUserAlreadyLogin()
    {
        $this->withSession([
            "user" => "andry"
        ])->post('/login', [
            "user" => "andry",
            "password" => "rahasia"
        ])->assertRedirect("/");
    }

    public function testLoginValidationError()
    {
        $this->post('/login', [])->assertSeeText("User or Password is required");
    }

    public function testLoginFailed()
    {
        $this->post('/login', [
            "user" => "andry",
            "password" => "wrong"
        ])->assertSeeText("User or Password wrong");
    }

    public function testLogout()
    {
        $this->withSession([
            "user" => "khannedy"
        ])->post('/logout')
            ->assertRedirect("/")
            ->assertSessionMissing("user");
    }

    public function testLogoutGuest()
    {
        $this->post('/logout')
            ->assertRedirect("/");
    }
}
