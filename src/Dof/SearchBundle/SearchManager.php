<?hh
namespace Dof\SearchBundle;

use Dof\SearchBundle\Intent\IntentInterface;

class SearchManager
{
    private $intents;

    public function __construct(private string $key) {
        $this->key = $key;
        $this->intents = [];
    }

    public function addIntent(string $intent, IntentInterface $service) {
        $this->intents[$intent] = $service;
    }

    public function process(?string $string) : ?string {
        if($string === null)
            return $this->notUnderstood();
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
        $intent = $answer['outcome']['intent'];

        if(!isset($this->indents[$intent]))
            return $this->notUnderstood();

        return $this->intents[$intent]->process($answer['outcome'][0]['entities'], $intent);
    }

    public function notUnderstood() {

    }
}
