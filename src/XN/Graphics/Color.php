<?php
namespace XN\Graphics;

class Color
{
	private $rgb;

	public function __construct($rgb = 0)
	{
		$this->rgb = $rgb;
	}

	public function getRed()
	{
		return ($this->rgb >> 16) & 0xFF;
	}
	public function setRed($red)
	{
		$this->rgb = ($this->rgb & ~0xFF0000) | (($red & 0xFF) << 16);
		return $this;
	}

	public function getGreen()
	{
		return ($this->rgb >> 8) & 0xFF;
	}
	public function setGreen($green)
	{
		$this->rgb = ($this->rgb & ~0xFF00) | (($green & 0xFF) << 8);
		return $this;
	}

	public function getBlue()
	{
		return $this->rgb & 0xFF;
	}
	public function setBlue($blue)
	{
		$this->rgb = ($this->rgb & ~0xFF) | ($blue & 0xFF);
		return $this;
	}

	public function getRGB()
	{
		return $this->rgb;
	}
	public function setRGB($rgb)
	{
		$this->rgb = $rgb;
		return $this;
	}

	public function getHex()
	{
		return '#' . str_pad(dechex($this->rgb), 6, '0', STR_PAD_LEFT);
	}
	public function setHex($hex)
	{
		$this->rgb = hexdec(ltrim('#', $hex));
		return $this;
	}

	public function __toString()
	{
		return $this->getHex();
	}

	public static function fromRGB($rgb)
	{
		return new self($rgb);
	}
	public static function fromRedGreenBlue($red, $green, $blue)
	{
		return new self((($red & 0xFF) << 16) | (($green & 0xFF) << 8) | ($blue & 0xFF));
	}
	public static function fromHex($hex)
	{
		return new self(hexdec(ltrim('#', $hex)));
	}
}
