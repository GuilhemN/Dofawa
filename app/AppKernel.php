<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator;

use XN\UtilityBundle\DependencyInjection\DelegatingContainerBuilder;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new FOS\MessageBundle\FOSMessageBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
            new EWZ\Bundle\RecaptchaBundle\EWZRecaptchaBundle(),
            new Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle(),

            new XN\UtilityBundle\XNUtilityBundle(),
            new SymfonyExtender\TranslationsBundle\SymfonyExtenderTranslationsBundle(),

            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),

            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),

            new Dof\Bundle\UserBundle\DofUserBundle(),
            new Dof\Bundle\CMSBundle\DofCMSBundle(),
            new Dof\Bundle\ItemBundle\DofItemBundle(),
            new Dof\Bundle\MainBundle\DofMainBundle(),
            new Dof\Bundle\ArtBundle\DofArtBundle(),
            new Dof\Bundle\MapBundle\DofMapBundle(),
            new Dof\Bundle\GraphicsBundle\DofGraphicsBundle(),
            new Dof\Bundle\AdminBundle\DofAdminBundle(),
            new Dof\Bundle\CharacterBundle\DofCharacterBundle(),
            new Dof\Bundle\ImpExpBundle\DofImpExpBundle(),
            new Dof\Bundle\Social\MessageBundle\DofMessageBundle(),
            new Dof\Bundle\TranslationBundle\DofTranslationBundle(),
            new Dof\Bundle\User\CharacterBundle\DofUserCharacterBundle(),
            new Dof\Bundle\Social\ForumBundle\DofForumBundle(),
            new Dof\Bundle\GuildBundle\DofGuildBundle(),
            new Dof\Bundle\MonsterBundle\DofMonsterBundle(),
            new Dof\Bundle\User\ItemBundle\DofUserItemBundle(),
            new Dof\Bundle\QuestBundle\DofQuestBundle(),
            new Dof\Bundle\SearchBundle\DofSearchBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Dof\Bundle\GeneratorBundle\DofGeneratorBundle();
        }

        return $bundles;
    }

	public function getContainerBaseClass()
	{
		return '\XN\UtilityBundle\DependencyInjection\DelegatingContainer';
	}

	protected function getContainerBuilder()
    {
        $container = new DelegatingContainerBuilder(new ParameterBag($this->getKernelParameters()));

        if (class_exists('ProxyManager\Configuration')) {
            $container->setProxyInstantiator(new RuntimeInstantiator());
        }

        return $container;
	}

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    public function init()
    {
        date_default_timezone_set( 'Europe/Paris' );
        parent::init();
    }
}
