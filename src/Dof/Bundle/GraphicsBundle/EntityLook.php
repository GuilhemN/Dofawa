<?php

namespace Dof\Bundle\GraphicsBundle;

class EntityLook implements \Serializable
{
    const AK_RENDERER_PATTERN = 'http://staticns\.ankama\.com/dofus/renderer/look/((?:[0-9a-f]{2})+)/';

    private $parent;
    private $clsid;
    private $index;

	private $bone;
	private $skins;
	private $colors;
	private $scaleX;
	private $scaleY;
	private $subEntities;

	public function __construct($look = null)
	{
        if ($look === null) {
            $this->parent = null;
            $this->clsid = null;
            $this->index = null;
    		$this->bone = 0;
    		$this->skins = array();
    		$this->colors = array();
    		$this->scaleX = 1;
    		$this->scaleY = 1;
    		$this->subEntities = array();
        } else
            $this->unserialize($look);
	}

    public function __clone()
    {
        $this->parent = null;
        $this->clsid = null;
        $this->index = null;
        $this->skins = array_slice($this->skins, 0);
        $this->colors = array_slice($this->colors, 0);
        $subents = $this->subEntities;
        $this->subEntities = array();
        foreach ($subents as $clsid => $se)
            foreach ($se as $index => $subent)
                $this->setSubEntity($clsid, $index, clone $subent);
    }

    public function getParent()
    {
        return $this->parent;
    }
    public function getClassId()
    {
        return $this->clsid;
    }
    public function getIndex()
    {
        return $this->index;
    }

    public function setBone($bone)
    {
        $this->bone = $bone;
        return $this;
    }
    public function getBone()
    {
        return $this->bone;
    }

    public function setSkins(array $skins)
    {
        $this->skins = $skins;
        return $this;
    }
    public function addSkin($skin)
    {
        $this->skins[] = $skin;
        return $this;
    }
    public function removeSkin($skin)
    {
        $key = array_search($skin, $this->skins);
        if ($key !== false)
            array_splice($this->skins, $key, 1);
        return $this;
    }
    public function getSkins()
    {
        return $this->skins;
    }

    public function setColors(array $colors)
    {
        $this->colors = $colors;
        return $this;
    }
    public function setColor($index, $color)
    {
        if ($color === null)
            return $this->removeColor($index);
        $this->colors[$index] = $color;
        return $this;
    }
    public function removeColor($index)
    {
        unset($this->colors[$index]);
        return $this;
    }
    public function getColor($index)
    {
        return $this->colors[$index];
    }
    public function getColors()
    {
        return $this->colors;
    }

    public function setScaleX($scaleX)
    {
        $this->scaleX = $scaleX;
        return $this;
    }
    public function getScaleX()
    {
        return $this->scaleX;
    }

    public function setScaleY($scaleY)
    {
        $this->scaleY = $scaleY;
        return $this;
    }
    public function getScaleY()
    {
        return $this->scaleY;
    }

    public function setScale($scaleX, $scaleY = null)
    {
        $this->scaleX = $scaleX;
        $this->scaleY = ($scaleY === null) ? $scaleX : $scaleY;
        return $this;
    }

    public function setSubEntities(array $subEntities)
    {
        $this->subEntities = $subEntities;
        return $this;
    }
    public function setSubEntity($clsid, $index, EntityLook $subEntity = null)
    {
        if ($subEntity === null)
            return $this->removeSubEntity($clsid, $index);
        if ($subEntity->parent !== null)
            throw new \Exception();
        if (!isset($this->subEntities[$clsid]))
            $this->subEntities[$clsid] = array();
        $this->subEntities[$clsid][$index] = $subEntity;
        $subEntity->parent = $this;
        $subEntity->clsid = $clsid;
        $subEntity->index = $index;
        return $this;
    }
    public function removeSubEntity($clsid, $index)
    {
        if (isset($this->subEntities[$clsid]) && isset($this->subEntities[$clsid][$index])) {
            $subent = $this->subEntities[$clsid][$index];
            $subent->parent = null;
            $subent->clsid = null;
            $subent->index = null;
            unset($this->subEntities[$clsid][$index]);
            if (count($this->subEntities[$clsid]) == 0)
                unset($this->subEntities[$clsid]);
        }
        return $this;
    }
    public function getSubEntity($clsid, $index)
    {
        if (isset($this->subEntities[$clsid]) && isset($this->subEntities[$clsid][$index]))
            return $this->subEntities[$clsid][$index];
        return null;
    }
    public function getSubEntities()
    {
        return $this->subEntities;
    }

    public function remove()
    {
        if ($this->parent !== null)
            $this->parent->removeSubEntity($this->clsid, $this->index);
    }

	public function unserialize($look)
	{
        $this->__construct(null);
		if ($look === null)
			throw new \Exception();
        if (preg_match('~^\s*' . self::AK_RENDERER_PATTERN . '~', $look, $matches))
            $look = hex2bin($matches[1]);
		$look = str_replace(' ', '', strtr($look, chr(9) . chr(10) . chr(13), '   '));
		$looklen = strlen($look);
		if ($looklen == 0 || $look[0] != '{' || $look[$looklen - 1] != '}')
			throw new \Exception();
		$parts = explode('|', substr($look, 1, $looklen - 2));
		if (count($parts) > 0)
			$this->setBone(intval($parts[0]));
		if (count($parts) > 1)
		{
			foreach (explode(',', $parts[1]) as $skin)
				if (strlen($skin))
					$this->addSkin(intval($skin));
		}
		if (count($parts) > 2)
		{
			foreach (explode(',', $parts[2]) as $color)
			{
				if (strlen($color))
				{
					$pos = strpos($color, '=');
					if ($pos === false || $pos == 0 || $pos == strlen($color) - 1)
						throw new \Exception();
					if ($color[$pos + 1] == '#')
						$this->setColor(intval(substr($color, 0, $pos)), hexdec(substr($color, $pos + 2)));
					else
						$this->setColor(intval(substr($color, 0, $pos)), intval(substr($color, $pos + 1)));
				}
			}
		}
		if (count($parts) > 3)
		{
			$pos = strpos($parts[3], ',');
			if ($pos === false)
				$this->setScale(intval($parts[3]) / 100);
			else {
				$this->setScaleX(intval(substr($parts[3], 0, $pos)) / 100);
				$this->setScaleY(intval(substr($parts[3], $pos + 1)) / 100);
			}
		}
		if (count($parts) > 4)
		{
			$subs = implode('|', array_slice($parts, 4));
			while (($lsubs = strlen($subs)) > 0) {
				$o = $l = 0;
				for (; $o < $lsubs; ++$o) {
					if ($subs[$o] == '{')
						++$l;
					elseif ($subs[$o] == '}') {
						if ($l == 0)
							throw new \Exception();
						--$l;
					} elseif ($subs[$o] == ',' && $l == 0)
						break;
				}
				if ($l > 0)
					throw new \Exception();
				$pos = strpos($subs, '@');
				if ($pos === false || $pos >= $o)
					throw new \Exception();
				$pos2 = strpos($subs, '=', $pos + 1);
				if ($pos2 === false || $pos2 >= $o)
					throw new \Exception();
				$subent = new self(substr($subs, $pos2 + 1, $o - $pos2 - 1));
				$this->setSubEntity(intval(substr($subs, 0, $pos)), intval(substr($subs, $pos + 1, $pos2 - $pos - 1)), $subent);
				$subs = ($o == $lsubs) ? '' : substr($subs, $o + 1);
			}
		}
	}
    public function serialize() { return $this->format(); }

	public function format($hex_colors = false)
	{
		$outsub = count($this->subEntities) > 0;
		$outsca = $outsub || $this->scaleX != 1 || $this->scaleY != 1;
		$outclr = $outsca || count($this->colors) > 0;
		$outskn = $outclr || count($this->skins) > 0;
		$value = '{' . $this->bone;
		if ($outskn)
			$value .= '|' . implode(',', $this->skins);
		if ($outclr)
		{
			$colors = array();
			ksort($this->colors);
			foreach ($this->colors as $ck => $cv)
                if ($cv !== null)
		            $colors[] = $ck . '=' . ($hex_colors ? ('#' . str_pad(strtoupper(dechex($cv)), 6, '0', STR_PAD_LEFT)) : $cv);
			$value .= '|' . implode(',', $colors);
		}
		if ($outsca)
		{
			$sx = round($this->scaleX * 100);
			$sy = round($this->scaleY * 100);
			$value .= '|' . $sx;
			if ($sy != $sx)
				$value .= ',' . $sy;
		}
		if ($outsub)
		{
			$subents = array();
			ksort($this->subEntities);
			foreach ($this->subEntities as $clsid => &$subs)
			{
				ksort($subs);
				foreach ($subs as $posid => $subent)
                    if ($subent !== null)
			            $subents[] = $clsid . '@' . $posid . '=' . $subent->format($hex_colors);
			}
			$value .= '|' . implode(',', $subents);
		}
		return $value . '}';
	}

	public function __toString() { return $this->format(); }
}
