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

