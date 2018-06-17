<?php

namespace App\Http\Controllers;

use App\Billing\FakePaymentGateway;
use App\Concert;
use Illuminate\Http\Request;

class ConcertOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $concertId)
    {
        $paymentGateway = new FakePaymentGateway();
        $concert = Concert::find($concertId);

        $amount = $concert->ticket_price * $request->get('ticket_quantity');
        $paymentGateway->charge($amount, $request->get('payment_gateway'));

        return response()->json([], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Concert  $concert
     * @return \Illuminate\Http\Response
     */
    public function show(Concert $concert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Concert  $concert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Concert $concert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Concert  $concert
     * @return \Illuminate\Http\Response
     */
    public function destroy(Concert $concert)
    {
        //
    }
}
