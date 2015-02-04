<?php
namespace Dof\Bundle\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\Common\D2PContentProvider;

use XN\Common\Unpacker;
use XN\Grammar\FileReader;

class D2PCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('dof:d2p:extract')
        ->setDescription('Extract a d2p file')
        ->addArgument('path', InputArgument::REQUIRED, 'Path of the d2p File')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('DofMapBundle:MapPosition');
        $d2pcp = new D2PContentProvider();
        $d2pcp->process($input->getArgument('path'));

        // foreach($d2pcp->enumerate() as $path){
            try{
                $id = '73401862';
                $ent = $repo->find($id);
                $map = $d2pcp->open($id % 10 .'/' . $id . '.dlm');
                $this->processMap($map, $ent);
                echo 'Réussi';
            }
            catch(\Exception $e) {
                throw $e;
            }
        // }
    }
    protected function processMap($map, $ent) {
        echo '[' . $ent->getX() . ',' .  $ent->getY() . "]\n";
        echo (int)$ent->isOutdoor();
        $re = new FileReader($map);
        $up = new Unpacker($re);
        $header = $up->readByte();
        if($header != 77){
            fseek($map, 0);
            $u = gzuncompress(stream_get_contents($map));
            $map = fopen('php://memory', "rw+");
            fwrite($map, $u);
            fseek($map, 0);
            unset($u);
            $re = new FileReader($map);
            $up = new Unpacker($re);
            $header = $up->readByte();
            if($header != 77)
                throw new \Exception('unknown file format');
        }
        $version = $up->readByte();
        $id = $up->readUnsignedInt();
        if($version >= 7) {
            $encrypted = $up->readBoolean();
            $encryptionVersion = $up->readByte();
            $dataLen = $up->readInt();
            $offset = $re->getState();
            if($encrypted){
                $key = [];
                $encryptedData = $up->readBytes(0, $dataLen);
                $re->setState($offset);
                $encryptedRelativeId = $up->readUnsignedInt();
                $encryptedMapType = $up->readByte();
                $encryptedSubAreaId = $up->readInt();
                $key += ['', '', '', ''];
                $key[] = chr($encryptedMapType ^ $ent->isOutdoor());

                var_dump($key);
                throw new \Exception('Encryption not supported');
            }
        }
        $relativeId = $up->readUnsignedInt();
        $mapType = $up->readByte();
        $subAreaId = $up->readInt();
    }
}
