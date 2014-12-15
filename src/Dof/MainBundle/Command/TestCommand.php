<?php
namespace Dof\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use XN\Grammar\Reader;
use XN\Grammar\StringReader;

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
        $source = new StringReader('essaie de [i]modéliser [i]les[/i] correspondances[/i] établies par [b][i]cette[/i] [u]phrase[/u][/b] ');
        $test = (bool) $source->eatCSpan('[');

        $output->writeln('' . var_dump($test) . '');
        $output->writeln('' . var_dump($source) . '');
        $output->writeln('' . var_dump($source->peek(INF, true)) . '');
    }

    protected function text(Reader $source){
        return (bool) $source->eatCSpan('[');
    }

    protected function tag(Reader $source) {
        $state = $source->getState();
        $match = (($openTag = $source->eatRegex('#\[([^\]/][^\]]+)\]#A')) !== null)
        && $this->message($source)
        && $source->eat('[/' . $openTag[1] . ']');
        if (!$match)
        $source->setState($state);
        $source->freeState($state); // nécessite un pull de mon dernier commit
        return $match;
    }

    protected function part(Reader $source) {
        return $this->text($source) || $this->tag($source);
    }

    protected function message(Reader $source) {
        if (!$this->part($source))
            return false;
        while ($this->part($source)) { }
            return true;
    }
}
