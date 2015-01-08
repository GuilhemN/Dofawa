<?hh
namespace Dof\SearchBundle;

use Dof\SearchBundle\Intent\IntentInterface;

class SearchManager
{
    private string $key;
    public function __construct(string $key ) {
        $this->key = $key;
        $this->indents = [];
    }

    public function addIntent(string $intent, IntentInterface $service) {
        $this->indents[$intent] = $service;
    }

    public function process(?string $string) {
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
        $intent = $answer['outcomes'][0]['intent'];

        if(!isset($this->indents[$intent]))
            return $this->notUnderstood();

        return $this->indents[$intent]->process($answer['outcomes'][0]['entities'], $intent);
    }

    public function notUnderstood() {

    }
}
