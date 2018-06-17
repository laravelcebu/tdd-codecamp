# tdd-codecamp

Step by step reference for the hands on session during the June 16, 2018 TDD codecamp

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

What things you need to install the software and how to install them

```
Give examples
```

### Installing

A step by step series of examples that tell you how to get a development env running

Say what the step will be

```
Give the example
```

And repeat

```
until finished
```

End with an example of getting some data out of the system or using it for a little demo

## Codecamp
Step by step documentation of the afternoon tdd hands on session

### Steps

1. Open your terminal, navigate to your development directory and execute the following command 
```
laravel new ticketbeast
```

2. Execute the following command
```
php artisan make:test PurchaseTicketsTest
```

3. Open the file app/tests/Feature/PurchaseTicketsTest.php and locate the following code 
```
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

Replace it with the following code
```
    /** @test */
    public function customer_can_purchase_tickets()
    {
    	
        // Arrange
        
        // Act
        
        // Assert
    }
```

Save the changes and run the test using the following command
```
./vendor/bin/phpunit --filter=customer_can_purchase_tickets
```

Which will give you the following result
```
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
This test did not perform any assertions

OK, but incomplete, skipped, or risky tests!
Tests: 1, Assertions: 0, Risky: 1.
```

4. Update the code to the following
```
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

Save the changes and run the test using the following command
```
./vendor/bin/phpunit --filter=customer_can_purchase_tickets
```

Which will give you the following result
```
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Error: Class 'Tests\Feature\Concert' not found
```

5. Create the Concert model by executing the following command
```
php artisan make:model Concert
```

Run the test using the following command
```
./vendor/bin/phpunit --filter=customer_can_purchase_tickets
```

Which will still give you the following result
```
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Error: Class 'Tests\Feature\Concert' not found
```

Let's fix that by updating the following code
```
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseTicketsTest extends TestCase
```

By aliasing our newly created Concert model
```
namespace Tests\Feature;

use App\Concert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseTicketsTest extends TestCase
```

Running the test again will now result to
```
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
InvalidArgumentException: Unable to locate factory with name [default] [App\Concert].
```

6. Create a concert factory by executing the following command
```
php artisan make:factory ConcertFactory --model="App\Concert"
```

Running the test
```
./vendor/bin/phpunit --filter=customer_can_purchase_tickets
```

Will now give us the following result
```
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Illuminate\Database\QueryException: SQLSTATE[HY000] [1045] Access denied for user 'homestead'@'localhost' (using password: YES) (SQL: insert into `concerts` (`updated_at`, `created_at`) values (2018-06-17 09:05:06, 2018-06-17 09:05:06))
```

7. Open the file ticketbeast\phpunit.xml and locate the following code
```
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
```
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

Save the changes and run the test again
```
./vendor/bin/phpunit --filter=customer_can_purchase_tickets
```

This will now result to the following error
```
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Illuminate\Database\QueryException: SQLSTATE[HY000]: General error: 1 no such table: concerts (SQL: insert into "concerts" ("updated_at", "created_at") values (2018-06-17 09:18:25, 2018-06-17 09:18:25))
```

8. Create a migration file by executing the following command
```
php artisan make:migration create_concerts_table --create=concerts
```

Running the test will still result to
```
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Illuminate\Database\QueryException: SQLSTATE[HY000]: General error: 1 no such table: concerts (SQL: insert into "concerts" ("updated_at", "created_at") values (2018-06-17 09:18:25, 2018-06-17 09:18:25))
```

Locate the following code in PurchaseTicketsTest.php 
```
class PurchaseTicketsTest extends TestCase
{
    /** @test */
    public function customer_can_purchase_tickets()
    {
```

And let's update it to 
```
class PurchaseTicketsTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
    public function customer_can_purchase_tickets()
    {
```

Running the test will now give us the following error
```
PHP Fatal error:  Trait 'Tests\Feature\DatabaseMigrations' not found in /ticketbeast/tests/Feature/PurchaseTicketsTest.php on line 12
```

Go back to PurchaseTicketsTest.php and locate the following code
```
use App\Concert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
```

Update it to the following code, save and run the test again
```
use App\Concert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
```

It should now give us the following error
```
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Expected status code 201 but received 404.
Failed asserting that false is true.
```

9. Create controller which will handle all concert ticket orders request by executing the following command
```
php artisan make:controller ConcertOrderController --model="App\Concert" --api
```

Open the file ticketbeast/Http/Controllers/ConcertOrderController.php and locate the following code
```
	public function store(Request $request)
    {
        //
    }
```

Let's update it to the following for now
```
	public function store(Request $request)
    {
        return response()->json([], 201);
    }
```

Save the changes and running the test will still result to the following error
```
1) Tests\Feature\PurchaseTicketsTest::customer_can_purchase_tickets
Expected status code 201 but received 404.
Failed asserting that false is true.
```

Open ticketbeast/routes/web.php and append the following code
```
Route::post('concerts/{concert}/orders', 'ConcertOrderController@store');
```

Run the test again and BOOM! Our first green light! (technically!)
```
PHPUnit 7.2.4 by Sebastian Bergmann and contributors.

.                                                                   1 / 1 (100%)

Time: 157 ms, Memory: 16.00MB
```

## Running the tests

Explain how to run the automated tests for this system

### Break down into end to end tests

Explain what these tests test and why

```
Give an example
```

### And coding style tests

Explain what these tests test and why

```
Give an example
```

## Deployment

Add additional notes about how to deploy this on a live system

## Built With

* [Dropwizard](http://www.dropwizard.io/1.0.2/docs/) - The web framework used
* [Maven](https://maven.apache.org/) - Dependency Management
* [ROME](https://rometools.github.io/rome/) - Used to generate RSS Feeds

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Billie Thompson** - *Initial work* - [PurpleBooth](https://github.com/PurpleBooth)

See also the list of [contributors](https://github.com/your/project/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration
* etc

