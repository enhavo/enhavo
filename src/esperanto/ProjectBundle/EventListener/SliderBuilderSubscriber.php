<?php
/**
 * SlideBuilderSubscriber.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ProjectBundle\EventListener;

use Doctrine\ORM\EntityManager;
use esperanto\AdminBundle\Event\RouteBuilderEvent;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use esperanto\AdminBundle\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\GenericEvent;
use esperanto\ProjectBundle\Entity\Slide;
use esperanto\ProjectBundle\Entity\Slider;
use esperanto\NewsBundle\Entity\News;

class SliderBuilderSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityRepository
     */
    protected $sliderRepository;

    /**
     * @var EntityRepository
     */
    protected $slideRepository;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityRepository $sliderRepository, EntityRepository $slideRepository, EntityManager $entityManager)
    {
        $this->sliderRepository = $sliderRepository;
        $this->slideRepository = $slideRepository;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'esperanto_slider.slide.build_index_route' => array('onSlideBuildIndexRoute', 1),
            'esperanto_slider.slide.build_table_route' => array('onSlideBuildTableRoute', 1),
            'esperanto_slider.slide.build_menu' => array('onBuildMenu', 1),
            'esperanto_slider.slide.build_edit_route' => array('onSlideBuildEditAndCreateRoute', 1),
            'esperanto_slider.slide.build_create_route' => array('onSlideBuildEditAndCreateRoute', 1),

            'esperanto_slider.slider.build_menu' => array('onBuildMenu', 1),
            'esperanto_slider.slider.build_edit_route' => array('onSliderBuildEditAndCreateRoute', 1),
            'esperanto_slider.slider.build_create_route' => array('onSliderBuildEditAndCreateRoute', 1),

            'esperanto_slider.slide.pre_create' => array('onSlideSave', 0),

            'esperanto_news.news.pre_delete' => array('onNewsPreDelete', 0),
            'esperanto_page.page.pre_delete' => array('onPagePreDelete', 0),
            'esperanto_reference.reference.pre_delete' => array('onReferencePreDelete', 0),
        );
    }

    public function onNewsPreDelete(GenericEvent $event)
    {
        /** @var $subject News */
        $subject = $event->getSubject();
        $slides = $this->slideRepository->findBy(array('news' => $subject));

        /** @var $slide Slide */
        foreach($slides as $slide) {
            if($slide->getLinkType() == 'news') {
                $slide->setLinkType(Slide::LINK_TYPE_EXTERNAL);
            }
            $slide->setNews(null);
        }
        $this->entityManager->flush();
    }

    public function onPagePreDelete(GenericEvent $event)
    {
        /** @var $subject News */
        $subject = $event->getSubject();
        $slides = $this->slideRepository->findBy(array('page' => $subject));

        /** @var $slide Slide */
        foreach($slides as $slide) {
            if($slide->getLinkType() == 'page') {
                $slide->setLinkType(Slide::LINK_TYPE_EXTERNAL);
            }
            $slide->setPage(null);
        }
        $this->entityManager->flush();
    }

    public function onReferencePreDelete(GenericEvent $event)
    {
        /** @var $subject News */
        $subject = $event->getSubject();
        $slides = $this->slideRepository->findBy(array('reference' => $subject));

        /** @var $slide Slide */
        foreach($slides as $slide) {
            if($slide->getLinkType() == 'news') {
                $slide->setLinkType(Slide::LINK_TYPE_EXTERNAL);
            }
            $slide->setReference(null);
        }
        $this->entityManager->flush();
    }

    public function onSlideBuildIndexRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setSorting(array('order' => 'asc'));
    }

    public function onSlideBuildTableRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setSorting(array('order' => 'asc'));
    }

    public function onSlideBuildEditAndCreateRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->getViewBuilder()
            ->setParameter('form_template', 'esperantoProjectBundle:Admin:Slide/form.html.twig');
    }

    public function onSliderBuildEditAndCreateRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->getViewBuilder()
            ->setParameter('manage_route', 'esperanto_slider_slider_edit');
        $event->getBuilder()->setTemplate('esperantoProjectBundle:Admin:Slider/form.html.twig');
    }

    public function onBuildMenu(MenuBuilderEvent $event)
    {
        $event->setBuilder(null);
    }

    public function onSlideSave(GenericEvent $event)
    {
        $slider = $this->sliderRepository->findOneBy(array('id' => 1));
        if(empty($slider)) {
            $slider = new Slider();
            $slider->setTitle('homepage');
            $this->entityManager->persist($slider);
            $this->entityManager->flush();
        }
        $order = 0;
        if($slider) {
            if($slider->getSlides()) {
                $order = count($slider->getSlides()->toArray());
            }

            /** @var $slide Slide */
            $slide = $event->getSubject();
            $slide->setSlider($slider);
            $slide->setOrder($order);
        }
    }
}