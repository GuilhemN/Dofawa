<?hh
namespace Dof\SearchBundle\Intent;

use Dof\UserBundle\Entity\ProgrammedNotification;

class AddNotificationIntent
{
    use IntentTrait;

    public function process(array $entities, $intent) : ?string {
        if(!$this->sc->isGranted('ROLE_USER'))
            return $this->render('DofSearchBundle:Error:must-be-connected.html.twig');
        $user = $this->sc->getToken()->getUser();
        $type = $entities['type']['value'];
        if($type == 'dragoturkey_childbirth') {
            $drg = $this->em->getRepository('DofItemsBundle:MountTemplate')->findOneByNameFr($entities['dragoturkey']['value']);
            if($drg === null)
                return 'Monture non trouvée.';
            $pn = new ProgrammedNotification;
            $pn
                ->setOwner($user)
                ->setType('dragoturkey_childbirth')
                ->setEntity($drg)
                ->setDate(new \Datetime('+6 hour'));
            $this->em->persist($pn);
            $this->em->flush($pn);
            return $this->render('DofSearchBundle:Intent:dragoturkey-childbirth.html.twig', ['drg' => $drg]);
        }


        return '';
    }
}
