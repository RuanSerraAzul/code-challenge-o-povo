<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JornalistaTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register()
    {
        $response = $this->get('/');
        $baseUrl= "http://localhost:8000/api/login";
        $email = "usuario@teste.com.br";
        $password = "senha123";

        $response = $this->json('POST', $baseUrl . '/', [
            'email' => $email,
            'password' => $password
        ]);

        $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'access_token', 'token_type', 'expires_in'
        ]);
    }
}
