<?php
namespace Dof\GeneratorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use PDO;
use Twig_Environment;

use XN\Common\Inflector;

class GenerateCharacteristicCodeCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('generate:dof:characteristic')
			->setDescription('Generates characteristic-specific code');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$fixes = [
			'percent-power' => 'power',
			'power-traps' => 'trap-power',
			'reflects-damage' => 'reflected-damage'
		];

		$db = $this->getContainer()->get('database_connection');
		$twig = $this->getContainer()->get('twig');

		$stmt = $db->prepare(<<<'EOQ'
SELECT idesc.value description, o.id, o.characteristic, o.bonusType FROM dofus2.D2O_Effect o
JOIN dofus2.D2I_en idesc ON o.descriptionId = idesc.id
WHERE o.id IN (SELECT DISTINCT effectId FROM dofus2.D2O_Item_possibleEffect)
AND o.characteristic <> 0
AND o.bonusType <> 0
ORDER BY o.effectPriority ASC
EOQ
		);
		$stmt->execute();
		$chara = [ ];
		while (($fx = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
			$charaID = intval($fx['characteristic']);
			$fxType = intval($fx['bonusType']);
			$fxID = intval($fx['id']);
			$slug = self::slugify($fx['description']);
			if (isset($chara[$charaID])) {
				if ($slug != $chara[$charaID]['slug'])
					$output->writeln('<error>Characteristic slug conflict for #' . $charaID . ' : ' . $chara[$charaID]['slug'] . ' / ' . $slug . '</error>');
				if ($fxType > 0)
					$chara[$charaID]['positiveEffect'] = $fxID;
				if ($fxType < 0)
					$chara[$charaID]['negativeEffect'] = $fxID;
			} else
				$chara[$charaID] = [
					'id' => $charaID,
					'slug' => $slug,
					'positiveEffect' => ($fxType > 0) ? $fxID : null,
					'negativeEffect' => ($fxType < 0) ? $fxID : null
				];
		}
		$chara = array_values($chara);
		foreach ($chara as &$row)
			if (isset($fixes[$row['slug']]))
				$row['slug'] = $fixes[$row['slug']];

		$context = [ 'characteristics' => $chara ];

		self::generate($twig, 'ItemsBundle:CharacteristicsMetadata', $context);
		self::generate($twig, 'ItemsBundle:CharacteristicsTrait', $context);
		self::generate($twig, 'ItemsBundle:CharacteristicsRangeTrait', $context);
	}

	private static function generate(Twig_Environment $twig, $elementName, $context)
	{
		$tpl = $twig->loadTemplate('DofGeneratorBundle:' . $elementName . '.php.twig');
		$element = $tpl->render($context);
		file_put_contents(dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . strtr($elementName, [ ':' => DIRECTORY_SEPARATOR ]) . '.php', $element);
	}

	private static function slugify($fxDesc)
	{
		$fxDesc = str_replace('#1{~1~2 to }#2', '{{ value }}', $fxDesc);
		$fxDesc = str_replace('-#1{~1~2 to -}#2', '{{ value }}', $fxDesc);
		$fxDesc = str_replace('-{{ value }}', '{{ value }}', $fxDesc);
		$fxDesc = str_replace('{{ value }}', '', $fxDesc);
		$fxDesc = str_replace('%', ' Percent ', $fxDesc);
		return Inflector::slugify($fxDesc);
	}
}
