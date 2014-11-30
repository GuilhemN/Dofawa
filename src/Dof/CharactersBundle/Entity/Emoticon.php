<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;

/**
 * Emoticon
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Dof\CharactersBundle\Entity\EmoticonRepository")
 */
class Emoticon implements IdentifiableInterface, TimestampableInterface, SluggableInterface, LocalizedNameInterface
{
    use TimestampableTrait, SluggableTrait, LocalizedNameTrait, ReleaseBoundTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="_order", type="integer")
     */
    private $order;

    /**
     * @var boolean
     *
     * @ORM\Column(name="aura", type="boolean")
     */
    private $aura;


    /**
    * @var string
    *
    * @ORM\Column(name="shortcut_fr", type="string", length=255)
    */
    private $shortcutFr;

    /**
    * @var string
    *
    * @ORM\Column(name="shortcut_en", type="string", length=255, nullable=true)
    */
    private $shortcutEn;

    /**
    * @var string
    *
    * @ORM\Column(name="shortcut_de", type="string", length=255, nullable=true)
    */
    private $shortcutDe;

    /**
    * @var string
    *
    * @ORM\Column(name="shortcut_es", type="string", length=255, nullable=true)
    */
    private $shortcutEs;

    /**
    * @var string
    *
    * @ORM\Column(name="shortcut_it", type="string", length=255, nullable=true)
    */
    private $shortcutIt;

    /**
    * @var string
    *
    * @ORM\Column(name="shortcut_pt", type="string", length=255, nullable=true)
    */
    private $shortcutPt;

    /**
    * @var string
    *
    * @ORM\Column(name="shortcut_ja", type="string", length=255, nullable=true)
    */
    private $shortcutJa;

    /**
    * @var string
    *
    * @ORM\Column(name="shortcut_ru", type="string", length=255, nullable=true)
    */
    private $shortcutRu;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return Emoticon
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set aura
     *
     * @param boolean $aura
     * @return Emoticon
     */
    public function setAura($aura)
    {
        $this->aura = $aura;

        return $this;
    }

    /**
     * Get aura
     *
     * @return boolean
     */
    public function getAura()
    {
        return $this->aura;
    }

    /**
    * Set shortcutFr
    *
    * @param string $shortcutFr
    * @return Emoticon
    */
    public function setShortcutFr($shortcutFr)
    {
        $this->shortcutFr = $shortcutFr;

        return $this;
    }

    /**
    * Get shortcutNameFr
    *
    * @return string
    */
    public function getShortcutFr()
    {
        return $this->shortcutNameFr;
    }

    /**
    * Set shortcutNameEn
    *
    * @param string $shortcutNameEn
    * @return Emoticon
    */
    public function setShortcutEn($shortcutNameEn)
    {
        $this->shortcutNameEn = $shortcutNameEn;

        return $this;
    }

    /**
    * Get shortcutNameEn
    *
    * @return string
    */
    public function getShortcutEn()
    {
        return $this->shortcutNameEn;
    }

    /**
    * Set shortcutNameDe
    *
    * @param string $shortcutNameDe
    * @return Emoticon
    */
    public function setShortcutDe($shortcutNameDe)
    {
        $this->shortcutNameDe = $shortcutNameDe;

        return $this;
    }

    /**
    * Get shortcutNameDe
    *
    * @return string
    */
    public function getShortcutDe()
    {
        return $this->shortcutDe;
    }

    /**
    * Set shortcutEs
    *
    * @param string $shortcutEs
    * @return Emoticon
    */
    public function setShortcutEs($shortcutEs)
    {
        $this->shortcutEs = $shortcutEs;

        return $this;
    }

    /**
    * Get shortcutEs
    *
    * @return string
    */
    public function getShortcutEs()
    {
        return $this->shortcutEs;
    }

    /**
    * Set shortcutIt
    *
    * @param string $shortcutIt
    * @return Emoticon
    */
    public function setShortcutIt($shortcutIt)
    {
        $this->shortcutIt = $shortcutIt;

        return $this;
    }

    /**
    * Get shortcutIt
    *
    * @return string
    */
    public function getShortcutIt()
    {
        return $this->shortcutIt;
    }

    /**
    * Set shortcutPt
    *
    * @param string $shortcutPt
    * @return Emoticon
    */
    public function setShortcutPt($shortcutPt)
    {
        $this->shortcutPt = $shortcutPt;

        return $this;
    }

    /**
    * Get longNamePt
    *
    * @return string
    */
    public function getShortcutPt()
    {
        return $this->longNamePt;
    }

    /**
    * Set shortcutNameJa
    *
    * @param string $shortcutNameJa
    * @return Emoticon
    */
    public function setShortcutJa($shortcutNameJa)
    {
        $this->shortcutNameJa = $shortcutNameJa;

        return $this;
    }

    /**
    * Get shortcutNameJa
    *
    * @return string
    */
    public function getShortcutJa()
    {
        return $this->shortcutNameJa;
    }

    /**
    * Set shortcutNameRu
    *
    * @param string $shortcutNameRu
    * @return Emoticon
    */
    public function setShortcutRu($shortcutNameRu)
    {
        $this->shortcutNameRu = $shortcutNameRu;

        return $this;
    }

    /**
    * Get shortcutNameRu
    *
    * @return string
    */
    public function getShortcutRu()
    {
        return $this->shortcutNameRu;
    }

    /**
    * Set shortcut
    *
    * @param string $shortcut
    * @param string $locale
    * @return Emoticon
    */
    public function setShortcut($shortcut, $locale = 'fr')
    {
        switch ($locale) {
            case 'fr': $this->shortcutFr = $shortcut; break;
            case 'en': $this->shortcutEn = $shortcut; break;
            case 'de': $this->shortcutDe = $shortcut; break;
            case 'es': $this->shortcutEs = $shortcut; break;
            case 'it': $this->shortcutIt = $shortcut; break;
            case 'pt': $this->shortcutPt = $shortcut; break;
            case 'ja': $this->shortcutJa = $shortcut; break;
            case 'ru': $this->shortcutRu = $shortcut; break;
        }
        return $this;
    }

    /**
    * Get shortcut
    *
    * @param string|array $locale
    * @return string
    */
    public function getShortcut($locale = 'fr')
    {
        if (is_array($locale)) {
            foreach ($locale as $loc) {
                $shortcut = $this->getShortcut($loc);
                if ($shortcut !== null)
                return $shortcut;
            }
            return null;
        }
        switch ($locale) {
            case 'fr': return $this->shortcutFr;
            case 'en': return $this->shortcutEn;
            case 'de': return $this->shortcutDe;
            case 'es': return $this->shortcutEs;
            case 'it': return $this->shortcutIt;
            case 'pt': return $this->shortcutPt;
            case 'ja': return $this->shortcutJa;
            case 'ru': return $this->shortcutRu;
            default: return null;
        }
    }

    public function __toString(){
        return $this->nameFr;
    }
}
