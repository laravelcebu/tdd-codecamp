<?php

namespace Tests\Feature;

use App\Concert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseTicketsTest extends TestCase
{
	use DatabaseMigrations;
	
    /** @test */
    public function customer_can_purchase_tickets()
    {
        // Arrange
        $concert = factory(Concert::class)->create();

        // Act
        $response = $this->post("concerts/{$concert->id}/orders", []);

        // Assert
        $response->assertStatus(201);
    } 
}
