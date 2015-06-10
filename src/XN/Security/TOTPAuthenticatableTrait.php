<?php

namespace XN\Security;

use Doctrine\ORM\Mapping as ORM;

trait TOTPAuthenticatableTrait
{
    /**
     * @ORM\Column(name="totp_secret_key", type="string", length=64, nullable=true)
     */
    protected $totpSecretKey;
    /**
     * @ORM\Column(name="totp_last_trial_stamp", type="integer", nullable=true)
     */
    protected $totpLastTrialStamp;
    /**
     * @ORM\Column(name="totp_last_success_stamp", type="integer", nullable=true)
     */
    protected $totpLastSuccessStamp;
    /**
     * @ORM\Column(name="totp_trial_count", type="integer", nullable=true)
     */
    protected $totpTrialCount;

    public function getTOTPSecretKey()
    {
        return $this->totpSecretKey;
    }
    public function setTOTPSecretKey($key)
    {
        $this->totpSecretKey = $key;

        return $this;
    }

    public function getTOTPLastTrialStamp()
    {
        return $this->totpLastTrialStamp;
    }
    public function setTOTPLastTrialStamp($stamp)
    {
        $this->totpLastTrialStamp = $stamp;

        return $this;
    }

    public function getTOTPLastSuccessStamp()
    {
        return $this->totpLastSuccessStamp;
    }
    public function setTOTPLastSuccessStamp($stamp)
    {
        $this->totpLastSuccessStamp = $stamp;

        return $this;
    }

    public function getTOTPTrialCount()
    {
        return $this->totpTrialCount;
    }
    public function setTOTPTrialCount($count)
    {
        $this->totpTrialCount = $count;

        return $this;
    }
}
