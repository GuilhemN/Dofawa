<?hh
namespace Dof\Bundle\SearchBundle\Intent;

class UserSearchIntent
{
    use IntentTrait;
    private $perPage = 15;

    public function process(array $entities, $intent) : ?string {
        $user = $this->em->getRepository('DofUserBundle:User')->findOneByUsername($entities['username']['value']);

        return $this->renderBlock('DofUserBundle:Profile:index.html.twig', 'body', ['user' => $user]);
    }
}
