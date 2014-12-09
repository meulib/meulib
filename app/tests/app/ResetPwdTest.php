<?php

class ResetPwdTest extends TestCase 
{
	public function testResetLinkSender()
	{
		// email not found, how can send you a reset link?
		$result = UserAccess::sendResetPwdLink('abragoogoo@foofoo.com');
		$this->assertFalse($result[0]);
		$this->assertEquals("Email Not Found",$result[1]);

		// ok, i sent it. hopefully. mail is only a pretense
		// while testing.
		$result = UserAccess::sendResetPwdLink($this->owner->EMail);
		$this->assertTrue($result[0]);

		// --- NOT TESTED
		// 1. failure in sending email
	}

	public function testResetLinkVerifier()
	{
		$result = UserAccess::sendResetPwdLink($this->owner->EMail);
		$userA = UserAccess::find($this->owner->UserID);

		// incorrect id
		$result = UserAccess::verifyPwdResetLink('booboo','beebee');
		$this->assertFalse($result[0]);
		$this->assertEquals($result[1],"ID/Code Not Found");

		// incorrect reset code
		$result = UserAccess::verifyPwdResetLink($this->owner->UserID,'beebee');
		$this->assertFalse($result[0]);
		$this->assertEquals($result[1],"ID/Code Not Found");

		$result = UserAccess::verifyPwdResetLink($this->owner->UserID,$userA->PwdResetHash);
		$this->assertTrue($result[0]);

		// --- NOT TESTED
		// 1. link expiry		
	}


	public function testPwdReseting()
	{
		$result = UserAccess::sendResetPwdLink($this->owner->EMail);
		$userA = UserAccess::find($this->owner->UserID);

		// incorrect id
		$result = UserAccess::resetPwd('booboo','beebee','uu');
		$this->assertFalse($result[0]);
		$this->assertEquals($result[1],"ID/Code Not Found");

		// incorrect reset code
		$result = UserAccess::resetPwd($this->owner->UserID,'beebee','uu');
		$this->assertFalse($result[0]);
		$this->assertEquals($result[1],"ID/Code Not Found");

		// pwd not valid
		$result = UserAccess::resetPwd($this->owner->UserID,$userA->PwdResetHash,'uu');
		$this->assertFalse($result[0]);
		$this->assertEquals($result[1],"Password Not Valid");

		$result = UserAccess::resetPwd($this->owner->UserID,$userA->PwdResetHash,'Somewhere');
		$this->assertTrue($result[0]);
	}
	

}