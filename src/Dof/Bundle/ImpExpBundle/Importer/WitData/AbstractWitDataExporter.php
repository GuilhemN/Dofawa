<?php
namespace Dof\Bundle\ImpExpBundle\Importer\WitData;

use XN\DependencyInjection\ServiceArray;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;

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

    protected $client;

    public function __construct(ServiceArray $sa, $key)
    {
        $this->dm = $sa[0];
        $this->key = $key;
        $this->client = new Client();
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
        return $this->client->get($this->generateUrl($path, $getParams), [
            'headers' => $this->getHeaders()
        ]);
    }

    protected function callPost($path, array $params, array $getParams = array()) {
        return $this->client->post($this->generateUrl($path, $getParams), [
            'body' => $params,
            'headers' => $this->getHeaders()
        ]);
    }

    protected function callPut($path, array $params, array $getParams = array()) {
        return $this->client->put($this->generateUrl($path, $getParams), [
            'json' => $params,
            'headers' => $this->getHeaders()
        ]);
    }

    protected function generateUrl($path, array $getParams = array()) {
        return URLBuilder::http_build_url('https://api.wit.ai/', [
                    'path' => $path,
                    'query' => URLBuilder::http_build_query((array) $getParams)
                ]);
    }

    protected function getHeaders() {
        return [ 'Authorization' => 'Bearer ' . $this->key ];
    }
}
