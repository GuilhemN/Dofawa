<?hh
namespace Dof\SearchBundle\Intent;

class UserSearchIntent
{
    use IntentTrait;
    private $perPage = 15;

    public function process(array $entities, $intent) : ?string {
        $user = $this->em->getRepository('DofUserBundle:User')->findOneByUsername($entities['item']['username'])

    return $this->tp->render('DofUserBundle:Profile:index.html.twig', ['user' => $user]);
}
}
