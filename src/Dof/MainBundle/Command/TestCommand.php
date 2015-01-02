<?php
namespace Dof\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use XN\Grammar\Reader;
use XN\Grammar\StringReader;
use Dof\ItemsBundle\Criteria\CriteriaParser;

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('guigui:test')
        ->setDescription('Test')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $replace = [
            '<textarea(.*?)>(.*?)<\/textarea>' => '[code]$2[/code]',
            
        ];
        $keys = array_map(function($v) {
            return '#' . $v . '#';
        }, array_keys($replace));

        preg_replace($keys, $replace, $content);
    }
}
