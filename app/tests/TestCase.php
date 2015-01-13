<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase 
{

  protected $owner = NULL;
  protected $otherOwner = NULL;
  protected $borrower = NULL;
  protected $eloquentCollectionType = 'Illuminate\Database\Eloquent\Collection';
  
  /**
   * Default preparation for each test
   */
  public function setUp()
  {
    parent::setUp();
  
    $this->prepareForTests();
  }

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
	}

  
  /**
   * Migrate the database
   */
  private function prepareForTests()
  {
    $_SERVER["REMOTE_ADDR"] = 'localhost';
    // Artisan::call('migrate', [
    //       '--force' => true
    //       ]);
    Artisan::call('db:seed');
    $this->owner = User::find("Owner1");
    $this->otherOwner = User::find("Owner2");
    $this->borrower = User::find("Borrower1");
    Mail::pretend();
  }
}
 
