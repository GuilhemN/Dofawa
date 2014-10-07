<?php
namespace XN\Security;

interface TOTPAuthenticatableInterface
{
	public function getTOTPSecretKey();

	public function getTOTPLastTrialStamp();
	public function setTOTPLastTrialStamp($stamp);

	public function getTOTPLastSuccessStamp();
	public function setTOTPLastSuccessStamp($stamp);

	public function getTOTPTrialCount();
	public function setTOTPTrialCount($count);
}
