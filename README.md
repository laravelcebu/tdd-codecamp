# tdd-codecamp

Step by step reference for the hands on session during the June 16, 2018 TDD codecamp.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine. See [commits](https://github.com/laravelcebu/tdd-codecamp/commits/master) section for reference to the actual code changes made at each step.

## Prerequisites

- Laravel 5.6

## Instructions
Step by step documentation of the afternoon tdd hands on session.

### Steps

1. Let's begin by creating a new Laravel application. Open your terminal, navigate to your development directory and execute the following command 
```sh
laravel new ticketbeast
```

2. Next we need to make our test file. This can be achieve using the following command
```sh
php artisan make:test PurchaseTicketsTest
```

3. Time to create our test case! Open the file `app/tests/Feature/PurchaseTicketsTest.php` and locate the following code 
```php
/**
 * A basic test example.
 *
 * @return void
 */
public function testExample()
{
    $this->assertTrue(true);
}
```

Replace it with this bit
```php
/** @test */
public function customer_can_purchase_tickets()
{
	
    // Arrange
    
    // Act
    
    // Assert
}
```

Save the changes and run the test using the following command
```sh
./vendor/bin/phpunit --filter=customer_can_purchase_tickets
```

Which will give you the following result
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
This test did not perform any assertions

OK, but incomplete, skipped, or risky tests!
Tests: 1, Assertions: 0, Risky: 1.
```

**NOTE: The filter option indicates that only the given test case will be executed**

4. Lets add a couple more lines to reflect the general idea of how the test case should look like
```php
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
```

Save the changes and run the test again. Please note that we will be using the same command to run the test unless specified otherwise.
```sh
./vendor/bin/phpunit --filter=customer_can_purchase_tickets
```

At this point you should be seeing the following error
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Error: Class 'Tests\Feature\Concert' not found
```

**TIP: Use the up arrow key to cycle thru your previously executed commands instead of typing or copy/paste to save time.**

5. Create the Concert model by executing the following command
```sh
php artisan make:model Concert
```

You will find out after running the test that we are still getting the same error
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Error: Class 'Tests\Feature\Concert' not found
```

Let's fix that by updating the following code
```php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseTicketsTest extends TestCase
```

By aliasing our newly created `Concert` model
```php
namespace Tests\Feature;

use App\Concert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseTicketsTest extends TestCase
```

Running the test again will now result to
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
InvalidArgumentException: Unable to locate factory with name [default] [App\Concert].
```

6. Create a concert factory by executing the following command
```sh
php artisan make:factory ConcertFactory --model="App\Concert"
```

Run the test and arrive at the following error
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Illuminate\Database\QueryException: SQLSTATE[HY000] [1045] Access denied for user 'homestead'@'localhost' (using password: YES) (SQL: insert into `concerts` (`updated_at`, `created_at`) values (2018-06-17 09:05:06, 2018-06-17 09:05:06))
```

7. Open the file ticketbeast\phpunit.xml and locate the following code
```php
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="BCRYPT_ROUNDS" value="4"/>
    <env name="CACHE_DRIVER" value="array"/>
    <env name="SESSION_DRIVER" value="array"/>
    <env name="QUEUE_DRIVER" value="sync"/>
    <env name="MAIL_DRIVER" value="array"/>
</php>
```

Update it to
```php
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="BCRYPT_ROUNDS" value="4"/>
    <env name="CACHE_DRIVER" value="array"/>
    <env name="SESSION_DRIVER" value="array"/>
    <env name="QUEUE_DRIVER" value="sync"/>
    <env name="MAIL_DRIVER" value="array"/>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
</php>
```

Save the changes and run the test again. This will now result to the following error
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Illuminate\Database\QueryException: SQLSTATE[HY000]: General error: 1 no such table: concerts (SQL: insert into "concerts" ("updated_at", "created_at") values (2018-06-17 09:18:25, 2018-06-17 09:18:25))
```

8. Create a migration file by executing the following command
```sh
php artisan make:migration create_concerts_table --create=concerts
```

Running the test again however will result to the same error

Locate the following code in `PurchaseTicketsTest.php`
```php
class PurchaseTicketsTest extends TestCase
{
    /** @test */
    public function customer_can_purchase_tickets()
    {
```

And let's update it to 
```php
class PurchaseTicketsTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
    public function customer_can_purchase_tickets()
    {
```

**NOTE: Adding this line will simply run the migration files automatically everytime the test is run**

Running the test will now give us the following error
```sh
PHP Fatal error:  Trait 'Tests\Feature\DatabaseMigrations' not found in /ticketbeast/tests/Feature/PurchaseTicketsTest.php on line 12
```

Go back to `PurchaseTicketsTest.php` and locate the following code
```php
use App\Concert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
```

Update it to the following code, save and run the test again
```php
use App\Concert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
```

It should now give us the following error
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Expected status code 201 but received 404.
Failed asserting that false is true.
```

9. Create controller which will handle all concert ticket order requests by executing the following command
```sh
php artisan make:controller ConcertOrderController --model="App\Concert" --api
```

Open the file `ticketbeast/Http/Controllers/ConcertOrderController.php` and locate the following code
```php
public function store(Request $request)
{
    //
}
```

Let's update it to the following for now
```php
public function store(Request $request)
{
    return response()->json([], 201);
}
```

Save the changes and run the test. This will still result to the same error.

Open `ticketbeast/routes/web.php` and append the following code
```php
Route::post('concerts/{concert}/orders', 'ConcertOrderController@store');
```

Run the test again and BOOM! Our first green light! :muscle: (technically!)
```sh
PHPUnit 7.2.4 by Sebastian Bergmann and contributors.

.                                                                   1 / 1 (100%)

Time: 157 ms, Memory: 16.00MB
```

10. Next, let's update our `customer_can_purchase_tickets` to the following code
```php
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
```

**NOTE: total charges = ticket price x ticket quantity = 3250 x 3 = 9750 **

Running the test will now give us the error
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Illuminate\Database\QueryException: SQLSTATE[HY000]: General error: 1 table concerts has no column named ticket_price (SQL: insert into "concerts" ("ticket_price", "updated_at", "created_at") values (3250, 2018-06-17 10:18:09, 2018-06-17 10:18:09))
```

11. Open the `create_concerts_table` migration file and locate the following code

**NOTE: Exact file names will be prefixed differently, _\<prefix\>_create_concerts_table.php_** 

```php
Schema::create('concerts', function (Blueprint $table) {
    $table->increments('id');
    $table->timestamps();
});
```

Add a column for ticket price, save and run the test
```php
Schema::create('concerts', function (Blueprint $table) {
    $table->increments('id');
    $table->integer('ticket_price');
    $table->timestamps();
});
```

At this point you should be seeing the following error
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
ErrorException: Undefined variable: paymentGateway
```

12. Create a folder `app/Billing` and create a file `FakePaymentGateway.php` under it

Open and update it's contents to 
```php
<?php

namespace App\Billing;

class FakePaymentGateway
{

}
```

Now let's go over to `PurchaseTicketsTest.php` and add the following code
```php
$paymentGateway = new FakePaymentGateway();
```

So that it now looks like this
```php
// Arrange
$paymentGateway = new FakePaymentGateway();
$concert = factory(Concert::class)->create([
    'ticket_price' => 3250
]);
``` 

After running the test again, the error should have now changed to
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Error: Class 'Tests\Feature\FakePaymentGateway' not found
```

Fixing the reference should be easy enough, like this
```php
use App\Billing\FakePaymentGateway;
use App\Concert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
```

Save, run the test and now we got ourselves a different error
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Error: Call to undefined method App\Billing\FakePaymentGateway::getValidTestToken()
```

Let's add the following method to `FakePaymentGateway.php`
```php
public function getValidTestToken()
{
    return 'valid-token';
}
```

Save and run again to arrive at the following error
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Error: Call to undefined method App\Billing\FakePaymentGateway::totalCharges()
```

This time let's update `FakePaymentGateway.php` so that it now looks like this
```php
class FakePaymentGateway
{
    protected $charges;

    public function __construct()
    {
        $this->charges = collect();
    }

    public function getValidTestToken()
    {
        return 'valid-token';
    }

    public function totalCharges()
    {
        return $this->charges->sum();
    }
}
```
**NOTE: Please refer to the official documentation for [collect](https://laravel.com/docs/5.6/collections)**

At this point the error should now be
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Failed asserting that 0 matches expected 9750.
```

13. Head over to `ConcertOrderController.php` and update the store method to
```php
public function store(Request $request, $concertId)
{
    $paymentGateway = new FakePaymentGateway();
    $concert = Concert::find($concertId);

    $amount = $concert->ticket_price * $request->get('ticket_quantity');
    $paymentGateway->charge($amount, $request->get('payment_gateway'));

    return response()->json([], 201);
}
```

Running the test will now result to
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Expected status code 201 but received 500.
Failed asserting that false is true.
```

Which isn't very helpful so let's open `app\Exceptions\Handler.php`, find and update the report method to
```php
public function report(Exception $exception)
{
    throw $exception;
    parent::report($exception);
}
```
**NOTE: During the actual hands on session, the report method was updated somewhere between step 8 and 9.**

If we run the test again it will now give us
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Symfony\Component\Debug\Exception\FatalThrowableError: Class 'App\Http\Controllers\FakePaymentGateway' not found
```

Ah, much better. Do remember to remove the throw before you publish your code, it is meant for debugging only!

Let's go ahead and add a reference to `FakePaymentGateway`
```php
use App\Billing\FakePaymentGateway;
use App\Concert;
use Illuminate\Http\Request;

class ConcertOrderController extends Controller
```

Once we have that the error should now be
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Symfony\Component\Debug\Exception\FatalThrowableError: Call to undefined method App\Billing\FakePaymentGateway::charge()
```

Go ahead and define that method
```php
public function charge($amount, $token)
{
    $this->charges->push($amount);
}
```

Save and run the test so the result will now be 
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Failed asserting that 0 matches expected 9750.
```

14. Let's recap
At the end of step 12 we had the following error
```sh
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Failed asserting that 0 matches expected 9750.
```

During step 13 we added the charge logic which is 
```
total charges = ticket price x ticket quantity
```

However we still arrived on the same error before at the beginning of step 13!

Why?

Remember this from `PurchaseTicketsTest.php`?
```php
$paymentGateway = new FakePaymentGateway();
```

We can see here that a new fake payment gateway is being initialized

Moving along the `customer_can_purchase_tickets` method you will see 
```php
$response = $this->post("concerts/{$concert->id}/orders", [
    'email'             => 'john@example.com',
    'ticket_quantity'   => 3,
    'payment_gateway'   => $paymentGateway->getValidTestToken()
]);
```

Which calls the store method in `ConcertOrderController.php` and a new fake payment gateway is being initialized 
```php
$paymentGateway = new FakePaymentGateway();
```

Even thou we added the charge logic and invoke the fake payment gateway charge method and seen below
```php
$amount = $concert->ticket_price * $request->get('ticket_quantity');
$paymentGateway->charge($amount, $request->get('payment_gateway'));
```

Because `$paymentGateway` in `PurchaseTicketsTest.php` is a different instance of `FakePaymentGateway` from that of `$paymentGateway` in `ConcertOrderController.php` it will not be able to receive the changes that have been made when the charge method was invoked in `ConcertOrderController.php`.

To resolve this we need to modify the `FakePaymentGateway` so it implements the Singleton design pattern, ergo it will return the same exact instance everytime it is initialized.

Let's get started.

Create a file named `PaymentGateway.php` under `ticketbeast\app\Billing` and with the following content
```php
<?php

namespace App\Billing;

interface PaymentGateway
{
    public function charge($amount, $token);
}
```

Update `FakePaymentGateway` so that it now looks like this
```php
class FakePaymentGateway implements PaymentGateway
```

Next update `ConcertOrderController.php` to
```php
use App\Billing\FakePaymentGateway;
use App\Billing\PaymentGateway;
use App\Concert;
use Illuminate\Http\Request;

class ConcertOrderController extends Controller
{
    protected $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }
```

And the store method to
```php
public function store(Request $request, $concertId)
{
    $concert = Concert::find($concertId);

    $amount = $concert->ticket_price * $request->get('ticket_quantity');
    $this->paymentGateway->charge($amount, $request->get('payment_gateway'));

    return response()->json([], 201);
}
```

Next we will update `PurchaseTicketsTest.php` to
```php
use App\Billing\FakePaymentGateway;
use App\Concert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseTicketsTest extends TestCase
```

And `customer_can_purchase_tickets` to
```php
public function customer_can_purchase_tickets()
{
    // Arrange
    $paymentGateway = new FakePaymentGateway();
    $this->app->instance(PaymentGateway::class, $paymentGateway);
    
    $concert = factory(Concert::class)->create([
        'ticket_price' => 3250
    ]);
```

If we run the test again, we should have arrive at a green light with a message similar or equal to
```sh
PHPUnit 7.2.4 by Sebastian Bergmann and contributors.

.                                                                   1 / 1 (100%)

Time: 149 ms, Memory: 16.00MB

OK (1 test, 2 assertions)
```

## Authors

* **Harlequin Doyon** - *Resource Speaker* - [harlekoy](https://github.com/harlekoy)
* **Laravel Cebu** - *Event organizers* - [laravelcebu](https://github.com/laravelcebu)
* **Ian Panara** - *Documentation* - [fatcodingbastard](https://github.com/fatcodingbastard)

See also the list of [contributors](https://github.com/your/project/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to anyone whose code was used
* Sponsors
* Event Organizers
* Resource speakers
* etc

