<?php
// This code is automatically generated by a tool

// You can regenerate it using the command :
// php app/console generate:dof:characteristic

// The template is at :
// src/Dof/GeneratorBundle/Resources/views/ItemsBundle/CharacteristicsTrait.php.twig

namespace Dof\ItemsBundle;

use Doctrine\ORM\Mapping as ORM;

trait CharacteristicsTrait
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="vitality", type="integer")
	 */
	protected $vitality;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="strength", type="integer")
	 */
	protected $strength;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="intelligence", type="integer")
	 */
	protected $intelligence;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="chance", type="integer")
	 */
	protected $chance;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="agility", type="integer")
	 */
	protected $agility;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="wisdom", type="integer")
	 */
	protected $wisdom;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="power", type="integer")
	 */
	protected $power;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="critical_hits", type="integer")
	 */
	protected $criticalHits;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="ap", type="integer")
	 */
	protected $ap;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="mp", type="integer")
	 */
	protected $mp;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="range_", type="integer")
	 */
	protected $range;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="summons", type="integer")
	 */
	protected $summons;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="damage", type="integer")
	 */
	protected $damage;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="neutral_damage", type="integer")
	 */
	protected $neutralDamage;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="earth_damage", type="integer")
	 */
	protected $earthDamage;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="fire_damage", type="integer")
	 */
	protected $fireDamage;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="water_damage", type="integer")
	 */
	protected $waterDamage;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="air_damage", type="integer")
	 */
	protected $airDamage;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="heals", type="integer")
	 */
	protected $heals;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="prospecting", type="integer")
	 */
	protected $prospecting;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="initiative", type="integer")
	 */
	protected $initiative;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="reflected_damage", type="integer")
	 */
	protected $reflectedDamage;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="percent_neutral_resistance", type="integer")
	 */
	protected $percentNeutralResistance;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="percent_earth_resistance", type="integer")
	 */
	protected $percentEarthResistance;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="percent_fire_resistance", type="integer")
	 */
	protected $percentFireResistance;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="percent_water_resistance", type="integer")
	 */
	protected $percentWaterResistance;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="percent_air_resistance", type="integer")
	 */
	protected $percentAirResistance;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="neutral_resistance", type="integer")
	 */
	protected $neutralResistance;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="earth_resistance", type="integer")
	 */
	protected $earthResistance;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="fire_resistance", type="integer")
	 */
	protected $fireResistance;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="water_resistance", type="integer")
	 */
	protected $waterResistance;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="air_resistance", type="integer")
	 */
	protected $airResistance;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="percent_neutral_resistance_in_pvp", type="integer")
	 */
	protected $percentNeutralResistanceInPvp;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="percent_earth_resistance_in_pvp", type="integer")
	 */
	protected $percentEarthResistanceInPvp;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="percent_fire_resistance_in_pvp", type="integer")
	 */
	protected $percentFireResistanceInPvp;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="percent_water_resistance_in_pvp", type="integer")
	 */
	protected $percentWaterResistanceInPvp;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="percent_air_resistance_in_pvp", type="integer")
	 */
	protected $percentAirResistanceInPvp;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="neutral_resistance_in_pvp", type="integer")
	 */
	protected $neutralResistanceInPvp;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="earth_resistance_in_pvp", type="integer")
	 */
	protected $earthResistanceInPvp;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="fire_resistance_in_pvp", type="integer")
	 */
	protected $fireResistanceInPvp;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="water_resistance_in_pvp", type="integer")
	 */
	protected $waterResistanceInPvp;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="air_resistance_in_pvp", type="integer")
	 */
	protected $airResistanceInPvp;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="lock_", type="integer")
	 */
	protected $lock;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="dodge", type="integer")
	 */
	protected $dodge;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="ap_reduction", type="integer")
	 */
	protected $apReduction;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="mp_reduction", type="integer")
	 */
	protected $mpReduction;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="ap_loss_resistance", type="integer")
	 */
	protected $apLossResistance;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="mp_loss_resistance", type="integer")
	 */
	protected $mpLossResistance;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="critical_damage", type="integer")
	 */
	protected $criticalDamage;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="critical_resistance", type="integer")
	 */
	protected $criticalResistance;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="pushback_damage", type="integer")
	 */
	protected $pushbackDamage;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="pushback_resistance", type="integer")
	 */
	protected $pushbackResistance;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="trap_power", type="integer")
	 */
	protected $trapPower;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="trap_damage", type="integer")
	 */
	protected $trapDamage;

	/**
	 * Set vitality
	 *
	 * @param integer $vitality
	 * @return object
	 */
	public function setVitality($vitality)
	{
		$this->vitality = $vitality;
		return $this;
	}

	/**
	 * Get vitality
	 *
	 * @return integer
	 */
	public function getVitality()
	{
		return $this->vitality;
	}

	/**
	 * Set strength
	 *
	 * @param integer $strength
	 * @return object
	 */
	public function setStrength($strength)
	{
		$this->strength = $strength;
		return $this;
	}

	/**
	 * Get strength
	 *
	 * @return integer
	 */
	public function getStrength()
	{
		return $this->strength;
	}

	/**
	 * Set intelligence
	 *
	 * @param integer $intelligence
	 * @return object
	 */
	public function setIntelligence($intelligence)
	{
		$this->intelligence = $intelligence;
		return $this;
	}

	/**
	 * Get intelligence
	 *
	 * @return integer
	 */
	public function getIntelligence()
	{
		return $this->intelligence;
	}

	/**
	 * Set chance
	 *
	 * @param integer $chance
	 * @return object
	 */
	public function setChance($chance)
	{
		$this->chance = $chance;
		return $this;
	}

	/**
	 * Get chance
	 *
	 * @return integer
	 */
	public function getChance()
	{
		return $this->chance;
	}

	/**
	 * Set agility
	 *
	 * @param integer $agility
	 * @return object
	 */
	public function setAgility($agility)
	{
		$this->agility = $agility;
		return $this;
	}

	/**
	 * Get agility
	 *
	 * @return integer
	 */
	public function getAgility()
	{
		return $this->agility;
	}

	/**
	 * Set wisdom
	 *
	 * @param integer $wisdom
	 * @return object
	 */
	public function setWisdom($wisdom)
	{
		$this->wisdom = $wisdom;
		return $this;
	}

	/**
	 * Get wisdom
	 *
	 * @return integer
	 */
	public function getWisdom()
	{
		return $this->wisdom;
	}

	/**
	 * Set power
	 *
	 * @param integer $power
	 * @return object
	 */
	public function setPower($power)
	{
		$this->power = $power;
		return $this;
	}

	/**
	 * Get power
	 *
	 * @return integer
	 */
	public function getPower()
	{
		return $this->power;
	}

	/**
	 * Set criticalHits
	 *
	 * @param integer $criticalHits
	 * @return object
	 */
	public function setCriticalHits($criticalHits)
	{
		$this->criticalHits = $criticalHits;
		return $this;
	}

	/**
	 * Get criticalHits
	 *
	 * @return integer
	 */
	public function getCriticalHits()
	{
		return $this->criticalHits;
	}

	/**
	 * Set ap
	 *
	 * @param integer $ap
	 * @return object
	 */
	public function setAp($ap)
	{
		$this->ap = $ap;
		return $this;
	}

	/**
	 * Get ap
	 *
	 * @return integer
	 */
	public function getAp()
	{
		return $this->ap;
	}

	/**
	 * Set mp
	 *
	 * @param integer $mp
	 * @return object
	 */
	public function setMp($mp)
	{
		$this->mp = $mp;
		return $this;
	}

	/**
	 * Get mp
	 *
	 * @return integer
	 */
	public function getMp()
	{
		return $this->mp;
	}

	/**
	 * Set range
	 *
	 * @param integer $range
	 * @return object
	 */
	public function setRange($range)
	{
		$this->range = $range;
		return $this;
	}

	/**
	 * Get range
	 *
	 * @return integer
	 */
	public function getRange()
	{
		return $this->range;
	}

	/**
	 * Set summons
	 *
	 * @param integer $summons
	 * @return object
	 */
	public function setSummons($summons)
	{
		$this->summons = $summons;
		return $this;
	}

	/**
	 * Get summons
	 *
	 * @return integer
	 */
	public function getSummons()
	{
		return $this->summons;
	}

	/**
	 * Set damage
	 *
	 * @param integer $damage
	 * @return object
	 */
	public function setDamage($damage)
	{
		$this->damage = $damage;
		return $this;
	}

	/**
	 * Get damage
	 *
	 * @return integer
	 */
	public function getDamage()
	{
		return $this->damage;
	}

	/**
	 * Set neutralDamage
	 *
	 * @param integer $neutralDamage
	 * @return object
	 */
	public function setNeutralDamage($neutralDamage)
	{
		$this->neutralDamage = $neutralDamage;
		return $this;
	}

	/**
	 * Get neutralDamage
	 *
	 * @return integer
	 */
	public function getNeutralDamage()
	{
		return $this->neutralDamage;
	}

	/**
	 * Set earthDamage
	 *
	 * @param integer $earthDamage
	 * @return object
	 */
	public function setEarthDamage($earthDamage)
	{
		$this->earthDamage = $earthDamage;
		return $this;
	}

	/**
	 * Get earthDamage
	 *
	 * @return integer
	 */
	public function getEarthDamage()
	{
		return $this->earthDamage;
	}

	/**
	 * Set fireDamage
	 *
	 * @param integer $fireDamage
	 * @return object
	 */
	public function setFireDamage($fireDamage)
	{
		$this->fireDamage = $fireDamage;
		return $this;
	}

	/**
	 * Get fireDamage
	 *
	 * @return integer
	 */
	public function getFireDamage()
	{
		return $this->fireDamage;
	}

	/**
	 * Set waterDamage
	 *
	 * @param integer $waterDamage
	 * @return object
	 */
	public function setWaterDamage($waterDamage)
	{
		$this->waterDamage = $waterDamage;
		return $this;
	}

	/**
	 * Get waterDamage
	 *
	 * @return integer
	 */
	public function getWaterDamage()
	{
		return $this->waterDamage;
	}

	/**
	 * Set airDamage
	 *
	 * @param integer $airDamage
	 * @return object
	 */
	public function setAirDamage($airDamage)
	{
		$this->airDamage = $airDamage;
		return $this;
	}

	/**
	 * Get airDamage
	 *
	 * @return integer
	 */
	public function getAirDamage()
	{
		return $this->airDamage;
	}

	/**
	 * Set heals
	 *
	 * @param integer $heals
	 * @return object
	 */
	public function setHeals($heals)
	{
		$this->heals = $heals;
		return $this;
	}

	/**
	 * Get heals
	 *
	 * @return integer
	 */
	public function getHeals()
	{
		return $this->heals;
	}

	/**
	 * Set prospecting
	 *
	 * @param integer $prospecting
	 * @return object
	 */
	public function setProspecting($prospecting)
	{
		$this->prospecting = $prospecting;
		return $this;
	}

	/**
	 * Get prospecting
	 *
	 * @return integer
	 */
	public function getProspecting()
	{
		return $this->prospecting;
	}

	/**
	 * Set initiative
	 *
	 * @param integer $initiative
	 * @return object
	 */
	public function setInitiative($initiative)
	{
		$this->initiative = $initiative;
		return $this;
	}

	/**
	 * Get initiative
	 *
	 * @return integer
	 */
	public function getInitiative()
	{
		return $this->initiative;
	}

	/**
	 * Set reflectedDamage
	 *
	 * @param integer $reflectedDamage
	 * @return object
	 */
	public function setReflectedDamage($reflectedDamage)
	{
		$this->reflectedDamage = $reflectedDamage;
		return $this;
	}

	/**
	 * Get reflectedDamage
	 *
	 * @return integer
	 */
	public function getReflectedDamage()
	{
		return $this->reflectedDamage;
	}

	/**
	 * Set percentNeutralResistance
	 *
	 * @param integer $percentNeutralResistance
	 * @return object
	 */
	public function setPercentNeutralResistance($percentNeutralResistance)
	{
		$this->percentNeutralResistance = $percentNeutralResistance;
		return $this;
	}

	/**
	 * Get percentNeutralResistance
	 *
	 * @return integer
	 */
	public function getPercentNeutralResistance()
	{
		return $this->percentNeutralResistance;
	}

	/**
	 * Set percentEarthResistance
	 *
	 * @param integer $percentEarthResistance
	 * @return object
	 */
	public function setPercentEarthResistance($percentEarthResistance)
	{
		$this->percentEarthResistance = $percentEarthResistance;
		return $this;
	}

	/**
	 * Get percentEarthResistance
	 *
	 * @return integer
	 */
	public function getPercentEarthResistance()
	{
		return $this->percentEarthResistance;
	}

	/**
	 * Set percentFireResistance
	 *
	 * @param integer $percentFireResistance
	 * @return object
	 */
	public function setPercentFireResistance($percentFireResistance)
	{
		$this->percentFireResistance = $percentFireResistance;
		return $this;
	}

	/**
	 * Get percentFireResistance
	 *
	 * @return integer
	 */
	public function getPercentFireResistance()
	{
		return $this->percentFireResistance;
	}

	/**
	 * Set percentWaterResistance
	 *
	 * @param integer $percentWaterResistance
	 * @return object
	 */
	public function setPercentWaterResistance($percentWaterResistance)
	{
		$this->percentWaterResistance = $percentWaterResistance;
		return $this;
	}

	/**
	 * Get percentWaterResistance
	 *
	 * @return integer
	 */
	public function getPercentWaterResistance()
	{
		return $this->percentWaterResistance;
	}

	/**
	 * Set percentAirResistance
	 *
	 * @param integer $percentAirResistance
	 * @return object
	 */
	public function setPercentAirResistance($percentAirResistance)
	{
		$this->percentAirResistance = $percentAirResistance;
		return $this;
	}

	/**
	 * Get percentAirResistance
	 *
	 * @return integer
	 */
	public function getPercentAirResistance()
	{
		return $this->percentAirResistance;
	}

	/**
	 * Set neutralResistance
	 *
	 * @param integer $neutralResistance
	 * @return object
	 */
	public function setNeutralResistance($neutralResistance)
	{
		$this->neutralResistance = $neutralResistance;
		return $this;
	}

	/**
	 * Get neutralResistance
	 *
	 * @return integer
	 */
	public function getNeutralResistance()
	{
		return $this->neutralResistance;
	}

	/**
	 * Set earthResistance
	 *
	 * @param integer $earthResistance
	 * @return object
	 */
	public function setEarthResistance($earthResistance)
	{
		$this->earthResistance = $earthResistance;
		return $this;
	}

	/**
	 * Get earthResistance
	 *
	 * @return integer
	 */
	public function getEarthResistance()
	{
		return $this->earthResistance;
	}

	/**
	 * Set fireResistance
	 *
	 * @param integer $fireResistance
	 * @return object
	 */
	public function setFireResistance($fireResistance)
	{
		$this->fireResistance = $fireResistance;
		return $this;
	}

	/**
	 * Get fireResistance
	 *
	 * @return integer
	 */
	public function getFireResistance()
	{
		return $this->fireResistance;
	}

	/**
	 * Set waterResistance
	 *
	 * @param integer $waterResistance
	 * @return object
	 */
	public function setWaterResistance($waterResistance)
	{
		$this->waterResistance = $waterResistance;
		return $this;
	}

	/**
	 * Get waterResistance
	 *
	 * @return integer
	 */
	public function getWaterResistance()
	{
		return $this->waterResistance;
	}

	/**
	 * Set airResistance
	 *
	 * @param integer $airResistance
	 * @return object
	 */
	public function setAirResistance($airResistance)
	{
		$this->airResistance = $airResistance;
		return $this;
	}

	/**
	 * Get airResistance
	 *
	 * @return integer
	 */
	public function getAirResistance()
	{
		return $this->airResistance;
	}

	/**
	 * Set percentNeutralResistanceInPvp
	 *
	 * @param integer $percentNeutralResistanceInPvp
	 * @return object
	 */
	public function setPercentNeutralResistanceInPvp($percentNeutralResistanceInPvp)
	{
		$this->percentNeutralResistanceInPvp = $percentNeutralResistanceInPvp;
		return $this;
	}

	/**
	 * Get percentNeutralResistanceInPvp
	 *
	 * @return integer
	 */
	public function getPercentNeutralResistanceInPvp()
	{
		return $this->percentNeutralResistanceInPvp;
	}

	/**
	 * Set percentEarthResistanceInPvp
	 *
	 * @param integer $percentEarthResistanceInPvp
	 * @return object
	 */
	public function setPercentEarthResistanceInPvp($percentEarthResistanceInPvp)
	{
		$this->percentEarthResistanceInPvp = $percentEarthResistanceInPvp;
		return $this;
	}

	/**
	 * Get percentEarthResistanceInPvp
	 *
	 * @return integer
	 */
	public function getPercentEarthResistanceInPvp()
	{
		return $this->percentEarthResistanceInPvp;
	}

	/**
	 * Set percentFireResistanceInPvp
	 *
	 * @param integer $percentFireResistanceInPvp
	 * @return object
	 */
	public function setPercentFireResistanceInPvp($percentFireResistanceInPvp)
	{
		$this->percentFireResistanceInPvp = $percentFireResistanceInPvp;
		return $this;
	}

	/**
	 * Get percentFireResistanceInPvp
	 *
	 * @return integer
	 */
	public function getPercentFireResistanceInPvp()
	{
		return $this->percentFireResistanceInPvp;
	}

	/**
	 * Set percentWaterResistanceInPvp
	 *
	 * @param integer $percentWaterResistanceInPvp
	 * @return object
	 */
	public function setPercentWaterResistanceInPvp($percentWaterResistanceInPvp)
	{
		$this->percentWaterResistanceInPvp = $percentWaterResistanceInPvp;
		return $this;
	}

	/**
	 * Get percentWaterResistanceInPvp
	 *
	 * @return integer
	 */
	public function getPercentWaterResistanceInPvp()
	{
		return $this->percentWaterResistanceInPvp;
	}

	/**
	 * Set percentAirResistanceInPvp
	 *
	 * @param integer $percentAirResistanceInPvp
	 * @return object
	 */
	public function setPercentAirResistanceInPvp($percentAirResistanceInPvp)
	{
		$this->percentAirResistanceInPvp = $percentAirResistanceInPvp;
		return $this;
	}

	/**
	 * Get percentAirResistanceInPvp
	 *
	 * @return integer
	 */
	public function getPercentAirResistanceInPvp()
	{
		return $this->percentAirResistanceInPvp;
	}

	/**
	 * Set neutralResistanceInPvp
	 *
	 * @param integer $neutralResistanceInPvp
	 * @return object
	 */
	public function setNeutralResistanceInPvp($neutralResistanceInPvp)
	{
		$this->neutralResistanceInPvp = $neutralResistanceInPvp;
		return $this;
	}

	/**
	 * Get neutralResistanceInPvp
	 *
	 * @return integer
	 */
	public function getNeutralResistanceInPvp()
	{
		return $this->neutralResistanceInPvp;
	}

	/**
	 * Set earthResistanceInPvp
	 *
	 * @param integer $earthResistanceInPvp
	 * @return object
	 */
	public function setEarthResistanceInPvp($earthResistanceInPvp)
	{
		$this->earthResistanceInPvp = $earthResistanceInPvp;
		return $this;
	}

	/**
	 * Get earthResistanceInPvp
	 *
	 * @return integer
	 */
	public function getEarthResistanceInPvp()
	{
		return $this->earthResistanceInPvp;
	}

	/**
	 * Set fireResistanceInPvp
	 *
	 * @param integer $fireResistanceInPvp
	 * @return object
	 */
	public function setFireResistanceInPvp($fireResistanceInPvp)
	{
		$this->fireResistanceInPvp = $fireResistanceInPvp;
		return $this;
	}

	/**
	 * Get fireResistanceInPvp
	 *
	 * @return integer
	 */
	public function getFireResistanceInPvp()
	{
		return $this->fireResistanceInPvp;
	}

	/**
	 * Set waterResistanceInPvp
	 *
	 * @param integer $waterResistanceInPvp
	 * @return object
	 */
	public function setWaterResistanceInPvp($waterResistanceInPvp)
	{
		$this->waterResistanceInPvp = $waterResistanceInPvp;
		return $this;
	}

	/**
	 * Get waterResistanceInPvp
	 *
	 * @return integer
	 */
	public function getWaterResistanceInPvp()
	{
		return $this->waterResistanceInPvp;
	}

	/**
	 * Set airResistanceInPvp
	 *
	 * @param integer $airResistanceInPvp
	 * @return object
	 */
	public function setAirResistanceInPvp($airResistanceInPvp)
	{
		$this->airResistanceInPvp = $airResistanceInPvp;
		return $this;
	}

	/**
	 * Get airResistanceInPvp
	 *
	 * @return integer
	 */
	public function getAirResistanceInPvp()
	{
		return $this->airResistanceInPvp;
	}

	/**
	 * Set lock
	 *
	 * @param integer $lock
	 * @return object
	 */
	public function setLock($lock)
	{
		$this->lock = $lock;
		return $this;
	}

	/**
	 * Get lock
	 *
	 * @return integer
	 */
	public function getLock()
	{
		return $this->lock;
	}

	/**
	 * Set dodge
	 *
	 * @param integer $dodge
	 * @return object
	 */
	public function setDodge($dodge)
	{
		$this->dodge = $dodge;
		return $this;
	}

	/**
	 * Get dodge
	 *
	 * @return integer
	 */
	public function getDodge()
	{
		return $this->dodge;
	}

	/**
	 * Set apReduction
	 *
	 * @param integer $apReduction
	 * @return object
	 */
	public function setApReduction($apReduction)
	{
		$this->apReduction = $apReduction;
		return $this;
	}

	/**
	 * Get apReduction
	 *
	 * @return integer
	 */
	public function getApReduction()
	{
		return $this->apReduction;
	}

	/**
	 * Set mpReduction
	 *
	 * @param integer $mpReduction
	 * @return object
	 */
	public function setMpReduction($mpReduction)
	{
		$this->mpReduction = $mpReduction;
		return $this;
	}

	/**
	 * Get mpReduction
	 *
	 * @return integer
	 */
	public function getMpReduction()
	{
		return $this->mpReduction;
	}

	/**
	 * Set apLossResistance
	 *
	 * @param integer $apLossResistance
	 * @return object
	 */
	public function setApLossResistance($apLossResistance)
	{
		$this->apLossResistance = $apLossResistance;
		return $this;
	}

	/**
	 * Get apLossResistance
	 *
	 * @return integer
	 */
	public function getApLossResistance()
	{
		return $this->apLossResistance;
	}

	/**
	 * Set mpLossResistance
	 *
	 * @param integer $mpLossResistance
	 * @return object
	 */
	public function setMpLossResistance($mpLossResistance)
	{
		$this->mpLossResistance = $mpLossResistance;
		return $this;
	}

	/**
	 * Get mpLossResistance
	 *
	 * @return integer
	 */
	public function getMpLossResistance()
	{
		return $this->mpLossResistance;
	}

	/**
	 * Set criticalDamage
	 *
	 * @param integer $criticalDamage
	 * @return object
	 */
	public function setCriticalDamage($criticalDamage)
	{
		$this->criticalDamage = $criticalDamage;
		return $this;
	}

	/**
	 * Get criticalDamage
	 *
	 * @return integer
	 */
	public function getCriticalDamage()
	{
		return $this->criticalDamage;
	}

	/**
	 * Set criticalResistance
	 *
	 * @param integer $criticalResistance
	 * @return object
	 */
	public function setCriticalResistance($criticalResistance)
	{
		$this->criticalResistance = $criticalResistance;
		return $this;
	}

	/**
	 * Get criticalResistance
	 *
	 * @return integer
	 */
	public function getCriticalResistance()
	{
		return $this->criticalResistance;
	}

	/**
	 * Set pushbackDamage
	 *
	 * @param integer $pushbackDamage
	 * @return object
	 */
	public function setPushbackDamage($pushbackDamage)
	{
		$this->pushbackDamage = $pushbackDamage;
		return $this;
	}

	/**
	 * Get pushbackDamage
	 *
	 * @return integer
	 */
	public function getPushbackDamage()
	{
		return $this->pushbackDamage;
	}

	/**
	 * Set pushbackResistance
	 *
	 * @param integer $pushbackResistance
	 * @return object
	 */
	public function setPushbackResistance($pushbackResistance)
	{
		$this->pushbackResistance = $pushbackResistance;
		return $this;
	}

	/**
	 * Get pushbackResistance
	 *
	 * @return integer
	 */
	public function getPushbackResistance()
	{
		return $this->pushbackResistance;
	}

	/**
	 * Set trapPower
	 *
	 * @param integer $trapPower
	 * @return object
	 */
	public function setTrapPower($trapPower)
	{
		$this->trapPower = $trapPower;
		return $this;
	}

	/**
	 * Get trapPower
	 *
	 * @return integer
	 */
	public function getTrapPower()
	{
		return $this->trapPower;
	}

	/**
	 * Set trapDamage
	 *
	 * @param integer $trapDamage
	 * @return object
	 */
	public function setTrapDamage($trapDamage)
	{
		$this->trapDamage = $trapDamage;
		return $this;
	}

	/**
	 * Get trapDamage
	 *
	 * @return integer
	 */
	public function getTrapDamage()
	{
		return $this->trapDamage;
	}

	/**
	 * Set all characteristics
	 *
	 * @param array $characteristics
	 * @param boolean $zeroMissing
	 * @return object
	 */
	public function setCharacteristics(array $characteristics, $zeroMissing = false)
	{
		if (isset($characteristics['vitality']))
			$this->vitality = $characteristics['vitality'];
		elseif ($zeroMissing)
			$this->vitality = 0;
		if (isset($characteristics['strength']))
			$this->strength = $characteristics['strength'];
		elseif ($zeroMissing)
			$this->strength = 0;
		if (isset($characteristics['intelligence']))
			$this->intelligence = $characteristics['intelligence'];
		elseif ($zeroMissing)
			$this->intelligence = 0;
		if (isset($characteristics['chance']))
			$this->chance = $characteristics['chance'];
		elseif ($zeroMissing)
			$this->chance = 0;
		if (isset($characteristics['agility']))
			$this->agility = $characteristics['agility'];
		elseif ($zeroMissing)
			$this->agility = 0;
		if (isset($characteristics['wisdom']))
			$this->wisdom = $characteristics['wisdom'];
		elseif ($zeroMissing)
			$this->wisdom = 0;
		if (isset($characteristics['power']))
			$this->power = $characteristics['power'];
		elseif ($zeroMissing)
			$this->power = 0;
		if (isset($characteristics['criticalHits']))
			$this->criticalHits = $characteristics['criticalHits'];
		elseif ($zeroMissing)
			$this->criticalHits = 0;
		if (isset($characteristics['ap']))
			$this->ap = $characteristics['ap'];
		elseif ($zeroMissing)
			$this->ap = 0;
		if (isset($characteristics['mp']))
			$this->mp = $characteristics['mp'];
		elseif ($zeroMissing)
			$this->mp = 0;
		if (isset($characteristics['range']))
			$this->range = $characteristics['range'];
		elseif ($zeroMissing)
			$this->range = 0;
		if (isset($characteristics['summons']))
			$this->summons = $characteristics['summons'];
		elseif ($zeroMissing)
			$this->summons = 0;
		if (isset($characteristics['damage']))
			$this->damage = $characteristics['damage'];
		elseif ($zeroMissing)
			$this->damage = 0;
		if (isset($characteristics['neutralDamage']))
			$this->neutralDamage = $characteristics['neutralDamage'];
		elseif ($zeroMissing)
			$this->neutralDamage = 0;
		if (isset($characteristics['earthDamage']))
			$this->earthDamage = $characteristics['earthDamage'];
		elseif ($zeroMissing)
			$this->earthDamage = 0;
		if (isset($characteristics['fireDamage']))
			$this->fireDamage = $characteristics['fireDamage'];
		elseif ($zeroMissing)
			$this->fireDamage = 0;
		if (isset($characteristics['waterDamage']))
			$this->waterDamage = $characteristics['waterDamage'];
		elseif ($zeroMissing)
			$this->waterDamage = 0;
		if (isset($characteristics['airDamage']))
			$this->airDamage = $characteristics['airDamage'];
		elseif ($zeroMissing)
			$this->airDamage = 0;
		if (isset($characteristics['heals']))
			$this->heals = $characteristics['heals'];
		elseif ($zeroMissing)
			$this->heals = 0;
		if (isset($characteristics['prospecting']))
			$this->prospecting = $characteristics['prospecting'];
		elseif ($zeroMissing)
			$this->prospecting = 0;
		if (isset($characteristics['initiative']))
			$this->initiative = $characteristics['initiative'];
		elseif ($zeroMissing)
			$this->initiative = 0;
		if (isset($characteristics['reflectedDamage']))
			$this->reflectedDamage = $characteristics['reflectedDamage'];
		elseif ($zeroMissing)
			$this->reflectedDamage = 0;
		if (isset($characteristics['percentNeutralResistance']))
			$this->percentNeutralResistance = $characteristics['percentNeutralResistance'];
		elseif ($zeroMissing)
			$this->percentNeutralResistance = 0;
		if (isset($characteristics['percentEarthResistance']))
			$this->percentEarthResistance = $characteristics['percentEarthResistance'];
		elseif ($zeroMissing)
			$this->percentEarthResistance = 0;
		if (isset($characteristics['percentFireResistance']))
			$this->percentFireResistance = $characteristics['percentFireResistance'];
		elseif ($zeroMissing)
			$this->percentFireResistance = 0;
		if (isset($characteristics['percentWaterResistance']))
			$this->percentWaterResistance = $characteristics['percentWaterResistance'];
		elseif ($zeroMissing)
			$this->percentWaterResistance = 0;
		if (isset($characteristics['percentAirResistance']))
			$this->percentAirResistance = $characteristics['percentAirResistance'];
		elseif ($zeroMissing)
			$this->percentAirResistance = 0;
		if (isset($characteristics['neutralResistance']))
			$this->neutralResistance = $characteristics['neutralResistance'];
		elseif ($zeroMissing)
			$this->neutralResistance = 0;
		if (isset($characteristics['earthResistance']))
			$this->earthResistance = $characteristics['earthResistance'];
		elseif ($zeroMissing)
			$this->earthResistance = 0;
		if (isset($characteristics['fireResistance']))
			$this->fireResistance = $characteristics['fireResistance'];
		elseif ($zeroMissing)
			$this->fireResistance = 0;
		if (isset($characteristics['waterResistance']))
			$this->waterResistance = $characteristics['waterResistance'];
		elseif ($zeroMissing)
			$this->waterResistance = 0;
		if (isset($characteristics['airResistance']))
			$this->airResistance = $characteristics['airResistance'];
		elseif ($zeroMissing)
			$this->airResistance = 0;
		if (isset($characteristics['percentNeutralResistanceInPvp']))
			$this->percentNeutralResistanceInPvp = $characteristics['percentNeutralResistanceInPvp'];
		elseif ($zeroMissing)
			$this->percentNeutralResistanceInPvp = 0;
		if (isset($characteristics['percentEarthResistanceInPvp']))
			$this->percentEarthResistanceInPvp = $characteristics['percentEarthResistanceInPvp'];
		elseif ($zeroMissing)
			$this->percentEarthResistanceInPvp = 0;
		if (isset($characteristics['percentFireResistanceInPvp']))
			$this->percentFireResistanceInPvp = $characteristics['percentFireResistanceInPvp'];
		elseif ($zeroMissing)
			$this->percentFireResistanceInPvp = 0;
		if (isset($characteristics['percentWaterResistanceInPvp']))
			$this->percentWaterResistanceInPvp = $characteristics['percentWaterResistanceInPvp'];
		elseif ($zeroMissing)
			$this->percentWaterResistanceInPvp = 0;
		if (isset($characteristics['percentAirResistanceInPvp']))
			$this->percentAirResistanceInPvp = $characteristics['percentAirResistanceInPvp'];
		elseif ($zeroMissing)
			$this->percentAirResistanceInPvp = 0;
		if (isset($characteristics['neutralResistanceInPvp']))
			$this->neutralResistanceInPvp = $characteristics['neutralResistanceInPvp'];
		elseif ($zeroMissing)
			$this->neutralResistanceInPvp = 0;
		if (isset($characteristics['earthResistanceInPvp']))
			$this->earthResistanceInPvp = $characteristics['earthResistanceInPvp'];
		elseif ($zeroMissing)
			$this->earthResistanceInPvp = 0;
		if (isset($characteristics['fireResistanceInPvp']))
			$this->fireResistanceInPvp = $characteristics['fireResistanceInPvp'];
		elseif ($zeroMissing)
			$this->fireResistanceInPvp = 0;
		if (isset($characteristics['waterResistanceInPvp']))
			$this->waterResistanceInPvp = $characteristics['waterResistanceInPvp'];
		elseif ($zeroMissing)
			$this->waterResistanceInPvp = 0;
		if (isset($characteristics['airResistanceInPvp']))
			$this->airResistanceInPvp = $characteristics['airResistanceInPvp'];
		elseif ($zeroMissing)
			$this->airResistanceInPvp = 0;
		if (isset($characteristics['lock']))
			$this->lock = $characteristics['lock'];
		elseif ($zeroMissing)
			$this->lock = 0;
		if (isset($characteristics['dodge']))
			$this->dodge = $characteristics['dodge'];
		elseif ($zeroMissing)
			$this->dodge = 0;
		if (isset($characteristics['apReduction']))
			$this->apReduction = $characteristics['apReduction'];
		elseif ($zeroMissing)
			$this->apReduction = 0;
		if (isset($characteristics['mpReduction']))
			$this->mpReduction = $characteristics['mpReduction'];
		elseif ($zeroMissing)
			$this->mpReduction = 0;
		if (isset($characteristics['apLossResistance']))
			$this->apLossResistance = $characteristics['apLossResistance'];
		elseif ($zeroMissing)
			$this->apLossResistance = 0;
		if (isset($characteristics['mpLossResistance']))
			$this->mpLossResistance = $characteristics['mpLossResistance'];
		elseif ($zeroMissing)
			$this->mpLossResistance = 0;
		if (isset($characteristics['criticalDamage']))
			$this->criticalDamage = $characteristics['criticalDamage'];
		elseif ($zeroMissing)
			$this->criticalDamage = 0;
		if (isset($characteristics['criticalResistance']))
			$this->criticalResistance = $characteristics['criticalResistance'];
		elseif ($zeroMissing)
			$this->criticalResistance = 0;
		if (isset($characteristics['pushbackDamage']))
			$this->pushbackDamage = $characteristics['pushbackDamage'];
		elseif ($zeroMissing)
			$this->pushbackDamage = 0;
		if (isset($characteristics['pushbackResistance']))
			$this->pushbackResistance = $characteristics['pushbackResistance'];
		elseif ($zeroMissing)
			$this->pushbackResistance = 0;
		if (isset($characteristics['trapPower']))
			$this->trapPower = $characteristics['trapPower'];
		elseif ($zeroMissing)
			$this->trapPower = 0;
		if (isset($characteristics['trapDamage']))
			$this->trapDamage = $characteristics['trapDamage'];
		elseif ($zeroMissing)
			$this->trapDamage = 0;
		return $this;
	}

	/**
	 * Get all characteristics
	 *
	 * @return array
	 */
	public function getCharacteristics()
	{
		return [
			'vitality' => $this->vitality,
			'strength' => $this->strength,
			'intelligence' => $this->intelligence,
			'chance' => $this->chance,
			'agility' => $this->agility,
			'wisdom' => $this->wisdom,
			'power' => $this->power,
			'criticalHits' => $this->criticalHits,
			'ap' => $this->ap,
			'mp' => $this->mp,
			'range' => $this->range,
			'summons' => $this->summons,
			'damage' => $this->damage,
			'neutralDamage' => $this->neutralDamage,
			'earthDamage' => $this->earthDamage,
			'fireDamage' => $this->fireDamage,
			'waterDamage' => $this->waterDamage,
			'airDamage' => $this->airDamage,
			'heals' => $this->heals,
			'prospecting' => $this->prospecting,
			'initiative' => $this->initiative,
			'reflectedDamage' => $this->reflectedDamage,
			'percentNeutralResistance' => $this->percentNeutralResistance,
			'percentEarthResistance' => $this->percentEarthResistance,
			'percentFireResistance' => $this->percentFireResistance,
			'percentWaterResistance' => $this->percentWaterResistance,
			'percentAirResistance' => $this->percentAirResistance,
			'neutralResistance' => $this->neutralResistance,
			'earthResistance' => $this->earthResistance,
			'fireResistance' => $this->fireResistance,
			'waterResistance' => $this->waterResistance,
			'airResistance' => $this->airResistance,
			'percentNeutralResistanceInPvp' => $this->percentNeutralResistanceInPvp,
			'percentEarthResistanceInPvp' => $this->percentEarthResistanceInPvp,
			'percentFireResistanceInPvp' => $this->percentFireResistanceInPvp,
			'percentWaterResistanceInPvp' => $this->percentWaterResistanceInPvp,
			'percentAirResistanceInPvp' => $this->percentAirResistanceInPvp,
			'neutralResistanceInPvp' => $this->neutralResistanceInPvp,
			'earthResistanceInPvp' => $this->earthResistanceInPvp,
			'fireResistanceInPvp' => $this->fireResistanceInPvp,
			'waterResistanceInPvp' => $this->waterResistanceInPvp,
			'airResistanceInPvp' => $this->airResistanceInPvp,
			'lock' => $this->lock,
			'dodge' => $this->dodge,
			'apReduction' => $this->apReduction,
			'mpReduction' => $this->mpReduction,
			'apLossResistance' => $this->apLossResistance,
			'mpLossResistance' => $this->mpLossResistance,
			'criticalDamage' => $this->criticalDamage,
			'criticalResistance' => $this->criticalResistance,
			'pushbackDamage' => $this->pushbackDamage,
			'pushbackResistance' => $this->pushbackResistance,
			'trapPower' => $this->trapPower,
			'trapDamage' => $this->trapDamage
		];
	}
}
