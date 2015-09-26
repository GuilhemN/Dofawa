<?php

namespace Dof\Bundle\TradingBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use XN\Common\Unpacker;
use XN\Grammar\StringReader;

class ValidationCommand extends ContainerAwareCommand
{
    const MIN_WEIGHT = 5;
    const MIN_PARTICIPANTS = 2;
    const MAX_AGE = 86400; // 1 jour

    protected function configure()
    {
        $this
        ->setName('dof:trading:valid')
        ->setDescription('Check if there are new valid trades.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $tradeRepository = $em->getRepository('DofTradingBundle:Trade');
        $queryBuilder = $em->createQueryBuilder();

        $validTrades = $queryBuilder
            ->select('t')
            ->from('DofTradingBundle:Trade', 't')

            ->andWhere('t.valid != 1')
            ->andWhere('t.createdAt > :date')
            ->setParameter('date', new \DateTime('-' . self::MAX_AGE.' second'))

            ->groupBy('t.item', 't.server', 't.price')
            ->andHaving('SUM(t.weight) >= :MIN_WEIGHT')
            ->setParameter('MIN_WEIGHT', self::MIN_WEIGHT)

            ->andHaving('COUNT(t.owner) >= :MIN_PARTICIPANTS')
            ->setParameter('MIN_PARTICIPANTS', self::MIN_PARTICIPANTS)

            ->getQuery()
            ->getResult();

        $rowsProcessed = 0;
        foreach($validTrades as $validTrade) {
            $trades = $tradeRepository->findBy([
                'server' => $validTrade->getServer(),
                'price' => $validTrade->getPrice(),
                'item' => $validTrade->getItem(),
            ]);

            foreach($trades as $trade) {
                $trade->setValid(true);
                $owner = $trade->getOwner();
                $owner->setWeight($owner->getWeight() + 1);
            }

            ++$rowsProcessed;
            if (($rowsProcessed % 50) == 0) {
                $em->flush();
                $em->clear();
            }
        }

        $em->flush();
        $em->clear();

        $output->writeLn($rowsProcessed.' prix traités.');
    }
}
