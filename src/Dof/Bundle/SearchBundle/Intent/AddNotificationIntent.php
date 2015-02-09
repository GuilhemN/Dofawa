<?hh
namespace Dof\Bundle\SearchBundle\Intent;

use Dof\Bundle\UserBundle\Entity\ProgrammedNotification;

class AddNotificationIntent
{
    use IntentTrait;

    public function process(array $entities, $intent) : ?string {
        if(!$this->sc->isGranted('ROLE_USER'))
            return $this->render('DofSearchBundle:Error:must-be-connected.html.twig');
        $user = $this->sc->getToken()->getUser();
        $type = $entities['type']['value'];
        if($type == 'dragoturkey_childbirth') {
            $drg = isset($entities['dragoturkey']) ?
                $this->em->getRepository('DofItemBundle:MountTemplate')->findOneByNameFr($entities['dragoturkey']['value'])
                : null;
            if($drg === null)
                return 'Monture non trouvée.';
            elseif($drg->getGestationDuration() === null)
                return 'Cette dragodinde ne peut pas s\'accoupler.';
            $pn = new ProgrammedNotification;
            $pn
                ->setOwner($user)
                ->setType('dragoturkey_childbirth')
                ->setEntity($drg)
                ->setDate(new \Datetime(sprintf('+%d hour', $drg->getGestationDuration())));
            $this->em->persist($pn);
            $this->em->flush($pn);
            return $this->render('DofSearchBundle:Intent:dragoturkey-childbirth.html.twig', ['drg' => $drg]);
        }


        return '';
    }
}
