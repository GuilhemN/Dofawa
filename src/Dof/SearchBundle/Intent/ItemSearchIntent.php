<?hh
namespace Dof\SearchBundle\Intent;

class ItemSearchIntent
{
    use IntentTrait;
    private $perPage = 15;

    public function process(array $entities, $intent) : ?string {
        $options = [
            'name' => $entities['item']['value'],
        ];
        $repo = $this->em->getRepository('DofItemsBundle:ItemTemplate');
        $count = $repo->countWithOptions($options, $this->getLocale());
        $items = $repo
            ->findWithOptions(
                $options,
                ['level' => 'DESC', 'name' . ucfirst($this->getLocale()) => 'ASC'],
                $this->perPage,
                0,
                $this->getLocale()
            );
        $pagination = array(
            'page' => 1,
            'route' => 'dof_items_homepage',
            'pages_count' => ceil($count / $this->perPage),
            'route_params' => [ 'items[name]' => $options['name'] ]
        );
        return $this->renderBlock('DofSearchBundle:Intent:items.html.twig', 'body', ['items' => $items, 'pagination' => $pagination]);
    }
}
