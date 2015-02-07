<?php
namespace Dof\Bundle\ImpExpBundle\Importer\WitData;

use XN\DependencyInjection\ServiceArray;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\Bundle\ImpExpBundle\ImporterInterface;
use Dof\Bundle\ImpExpBundle\ImporterFlags;

use XN\Common\URLBuilder;

abstract class AbstractWitDataExporter implements ImporterInterface
{
    /**
     * @var ObjectManager
     */
    protected $dm;

    protected $key;

    public function __construct(ServiceArray $sa, $key)
    {
        $this->dm = $sa[0];
        $this->key = $key;
    }

    public function import($dataSet, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $this->dm->clear();
        $this->doImport($output);
        if (($flags & ImporterFlags::DRY_RUN) == 0)
            $this->dm->flush();
        $this->dm->clear();
    }

    protected abstract function doImport(OutputInterface $output = null);

    protected function callGet($path, array $getParams = array()) {
        return $this->call('get', $path, [], $getParams);
    }

    protected function callPost($path, array $params, array $getParams = array()) {
        return $this->call('post', $path, $params, $getParams);
    }

    protected function callPut($path, array $params, array $getParams = array()) {
        return $this->call('put', $path, $params, $getParams);
    }

    protected function callDelete($path, array $params, array $getParams = array()) {
        return $this->call('delete', $path, $params, $getParams);
    }

    protected function call($method, $path, array $params = array(), array $getParams = array()) {
        if(!empty($params))
            $context = stream_context_create(
                array_replace_recursive(
                    $c = $this->createContext($method, 'Content-type: application/json\r\n'),
                    [
                        'http' => [
                            'content' => json_encode($params)
                        ]
                    ]
                )
            );
        else
            $context = stream_context_create($this->createContext($method));
        return json_decode(
            file_get_contents(
                URLBuilder::http_build_url(
                    'https://api.wit.ai/',
                    [
                        'path' => $path,
                        'query' => URLBuilder::http_build_query((array) $getParams)
                    ]
                ), false, $context)
        , true);
    }

    protected function createContext($method, $header = '') {
        return [
        'http' => [
            'method' => strtoupper($method),
            'header' => "Authorization: Bearer " . $this->key . "\r\n" .
                "Accept: application/vnd.wit.20140401+json\r\n" .
                $header
            ]
        ];
    }
}
