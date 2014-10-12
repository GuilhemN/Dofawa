<?php
namespace XN\UtilityBundle;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use Doctrine\Common\Persistence\ObjectManager;

use XN\Security\TOTPAuthenticatableInterface;

class TOTPAuthenticationListener
{
	private $em;
	private $maxTrials;

	public static $lut = [
		"A" => 0,	"B" => 1,
		"C" => 2,	"D" => 3,
		"E" => 4,	"F" => 5,
		"G" => 6,	"H" => 7,
		"I" => 8,	"J" => 9,
		"K" => 10,	"L" => 11,
		"M" => 12,	"N" => 13,
		"O" => 14,	"P" => 15,
		"Q" => 16,	"R" => 17,
		"S" => 18,	"T" => 19,
		"U" => 20,	"V" => 21,
		"W" => 22,	"X" => 23,
		"Y" => 24,	"Z" => 25,
		"2" => 26,	"3" => 27,
		"4" => 28,	"5" => 29,
		"6" => 30,	"7" => 31
	];

	public function __construct(ObjectManager $em, $maxTrials)
	{
		$this->em = $em;
		$this->maxTrials = $maxTrials;
	}

	public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
	{
		$user = $event->getAuthenticationToken()->getUser();
		if (!$user)
			return;
		if (!($user instanceof TOTPAuthenticatableInterface))
			return;
		$key = $user->getTOTPSecretKey();
		if (!$key)
			return;
		$stamp = self::getCurrentStamp();
		if ($user->getTOTPLastTrialStamp() === null || $user->getTOTPLastTrialStamp() < $stamp) {
			$user->setTOTPLastTrialStamp($stamp);
			$user->setTOTPTrialCount(0);
		}
		if ($user->getTOTPTrialCount() >= $this->maxTrials)
			throw new AuthenticationException("Bad credentials");
		$request = $event->getRequest();
		$totp = trim($request->request->get('_totp'));
		if (!preg_match('/^[0-9]{6}$/', $totp))
			throw new AuthenticationException("Bad credentials");
		$totp = intval($totp);
		if (self::hash($stamp + 1, $key) == $totp)
			$totpStamp = $stamp + 1;
		elseif (self::hash($stamp, $key) == $totp)
			$totpStamp = $stamp;
		elseif (self::hash($stamp - 1, $key) == $totp)
			$totpStamp = $stamp - 1;
		else
			$totpStamp = null;
		if ($totpStamp !== null && ($user->getTOTPLastSuccessStamp() === null || $totpStamp > $user->getTOTPLastSuccessStamp())) {
			$user->setTOTPLastTrialStamp(max($user->getTOTPLastTrialStamp(), $totpStamp));
			$user->setTOTPLastSuccessStamp(($user->getTOTPLastSuccessStamp() === null) ? $totpStamp : max($user->getTOTPLastSuccessStamp(), $totpStamp));
			$user->setTOTPTrialCount($this->maxTrials);
			$this->em->persist($user);
			$this->em->flush($user);
		} else {
			$user->setTOTPTrialCount($user->getTOTPTrialCount() + 1);
			$this->em->persist($user);
			$this->em->flush($user);
			throw new AuthenticationException("Bad credentials");
		}
	}

	public static function getCurrentStamp()
	{
		return floor(time() / 30);
	}
	public static function hash($stamp, $key)
	{
		$key = self::base32_decode($key);
		if (strlen($key) < 8)
			throw new Exception('Secret key is too short. Must be at least 16 base 32 characters');

		$bin_counter = pack('N*', 0) . pack('N*', $stamp);
		$hash 	 = hash_hmac ('sha1', $bin_counter, $key, true);

		return self::oath_truncate($hash);
	}

	private static function base32_decode($b32)
	{

		$b32 	= strtoupper($b32);

		if (!preg_match('/^[ABCDEFGHIJKLMNOPQRSTUVWXYZ234567]+$/', $b32, $match))
			throw new Exception('Invalid characters in the base32 string.');

		$l 	= strlen($b32);
		$n	= 0;
		$j	= 0;
		$binary = "";

		for ($i = 0; $i < $l; $i++) {

			$n = $n << 5; 				// Move buffer left by 5 to make room
			$n = $n + self::$lut[$b32[$i]]; 	// Add value into buffer
			$j = $j + 5;				// Keep track of number of bits in buffer

			if ($j >= 8) {
				$j = $j - 8;
				$binary .= chr(($n & (0xFF << $j)) >> $j);
			}
		}

		return $binary;
	}
	private static function oath_truncate($hash)
	{
	    $offset = ord($hash[19]) & 0xf;

	    return (
	        ((ord($hash[$offset+0]) & 0x7f) << 24 ) |
	        ((ord($hash[$offset+1]) & 0xff) << 16 ) |
	        ((ord($hash[$offset+2]) & 0xff) << 8 ) |
	        (ord($hash[$offset+3]) & 0xff)
	    ) % 1000000;
	}
}
