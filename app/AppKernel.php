<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

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
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new CCDNForum\ForumBundle\CCDNForumForumBundle(),

            new XN\DataBundle\XNDataBundle(),
            new XN\UtilityBundle\XNUtilityBundle(),
            new SymfonyExtender\TranslationsBundle\SymfonyExtenderTranslationsBundle(),

            new Dof\UserBundle\DofUserBundle(),
            new Dof\ArticlesBundle\DofArticlesBundle(),
            new Dof\ItemsBundle\DofItemsBundle(),
            new Dof\MainBundle\DofMainBundle(),
            new Dof\ArtBundle\DofArtBundle(),
            new Dof\MapBundle\DofMapBundle(),
            new Dof\GraphicsBundle\DofGraphicsBundle(),
            new Dof\AdminBundle\DofAdminBundle(),
            new Dof\CharactersBundle\DofCharactersBundle(),

        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Dof\GeneratorBundle\DofGeneratorBundle();
        }

        return $bundles;
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
