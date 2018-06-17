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
        $concert = factory(Concert::class)->create([
        	'ticket_price' => 3250
        ]);

        // Act
        $response = $this->post("concerts/{$concert->id}/orders", [
        	'email' 			=> 'john@example.com',
            'ticket_quantity' 	=> 3,
            'payment_gateway' 	=> $paymentGateway->getValidTestToken()
        ]);

        // Assert
        $response->assertStatus(201);

        $this->assertEquals(9750, $paymentGateway->totalCharges());
    } 
}
