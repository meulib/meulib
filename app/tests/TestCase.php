<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase 
{

  protected $ownerUser = NULL;
  protected $borrowerUser = NULL;
  
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
    Artisan::call('db:seed');
    $this->ownerUser = User::find("OZJM1549672278");
    $this->borrowerUser = User::find("CICN2554151496");
    Mail::pretend();
  }
}
 
