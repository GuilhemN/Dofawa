<?hh
namespace Dof\SearchBundle\Intent;

class ItemSearchIntent
{
    use IntentTrait;

    public function process(array $entities, $intent) : ?string {
        $options = [
            'name' => $entities['item']['value'],
        ];
        $items = $this->em->getRepository('DofItemsBundle:ItemTemplate')
            ->findWithOptions($options, [], 1, null, $this->getLocale());

        return !empty($items) ? $this->tp->render('DofItemsBundle::item.html.twig', ['item' => $items[0]]) : null;
    }
}
