<?hh
namespace Dof\Bundle\SearchBundle;

use Dof\Bundle\SearchBundle\Intent\IntentInterface;

use Doctrine\Common\Cache\Cache;

class SearchManager
{
    private $intents;

    public function __construct(private Cache $ca, private string $key) {
        $this->intents = [];
    }

    public function addIntent(string $intent, IntentInterface $service) {
        $this->intents[$intent] = $service;
    }

    public function process(?string $string) : ?string {
        if(strlen($string) > 150)
            throw new \Exception('String too long.');
        if($string === null)
            return $this->notUnderstood();

        if ($searchMessageString = $this->ca->fetch(md5('search-message/' . $string))) {
            $answer = unserialize($searchMessageString);
        } else {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => "Authorization: Bearer " . $this->key . "\r\n" .
                        "Accept: application/vnd.wit.20140401+json\r\n"
                    ]
                ]);
            $answer = json_decode(
                file_get_contents('https://api.wit.ai/message?q=' . urlencode($string), false, $context)
            , true);
            $this->ca->save(md5('search-message/' . $string), serialize($answer));
        }

        $intent = $answer['outcome']['intent'];

        if(!isset($this->intents[$intent]))
            return $this->notUnderstood();

        return $this->intents[$intent]->process($answer['outcome']['entities'], $intent);
    }

    public function notUnderstood() {
        return "Votre requête n'a pas été comprise.";
    }
}
