<?php
namespace Dof\CharactersBundle;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use XN\Persistence\IdentifiableInterface;
use XN\Common\ServiceWithContainer;

use Dof\Common\PseudoRepositoriesTrait;

use Dof\CharactersBundle\Entity\SpellRankEffect;

class EffectTLoader extends ServiceWithContainer
{
    use PseudoRepositoriesTrait;

    /**
     * @var boolean
     *
     * Sometimes you may want to disable automatic parameter loading,
     * for example when importing data
     */
    private $enabled;

    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $this->enabled = true;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $ent = $args->getEntity();
        if ($this->enabled && $ent instanceof SpellRankEffect) {
			$ent->setTargets(array_map(function ($target) use ($em) {
				$hyTarget = [ ];
				if ($target[0] == '*') {
					$target = substr($target, 1);
					$hyTarget[] = '*';
				}
				$len = strlen($target);
				$pos = strcspn($target, '0123456789');
				if ($pos == $len)
					$hyTarget[] = $target;
				else {
					$type = substr($target, 0, $pos);
					$parm = intval(substr($target, $pos));
					switch (strtolower($type)) {
						case 'b':
							$parm = $em->find('DofCharactersBundle:Breed', $parm);
							break;
						case 'e':
							$parm = $em->find('DofCharactersBundle:State', $parm);
							break;
						case 'f':
							$parm = $em->find('DofMonstersBundle:Monster', $parm);
							break;
					}
					$hyTarget[] = $type;
					$hyTarget[] = $parm;
				}
				return $hyTarget;
			}, $ent->getRawTargets());
			$ent->setTriggers(array_map(function ($trigger) use ($em) {
				$hyTrigger = [ ];
				$len = strlen($trigger);
				$pos = strcspn($trigger, '0123456789');
				if ($pos == $len)
					$hyTrigger[] = $trigger;
				else {
					$type = substr($trigger, 0, $pos);
					$parm = intval(substr($trigger, $pos));
					switch (strtolower($type)) {
						case 'eo':
							$parm = $em->find('DofCharactersBundle:State', $parm);
							break;
					}
					$hyTrigger[] = $type;
					$hyTrigger[] = $parm;
				}
				return $hyTrigger;
			}, $ent->getRawTriggers()));
        }
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }
}
