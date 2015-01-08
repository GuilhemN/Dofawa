<?hh
namespace Dof\SearchBundle\Intent;

class ItemSearchIntent implements IntentInterface
{
    public function process(array $entities, $intent) : string {
        return 'test';
    }
}
