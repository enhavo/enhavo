<?php

namespace Enhavo\Bundle\ArticleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\RouterInterface;

class ArticleType extends AbstractType
{
    /**
     * @var string
     */
    protected $dataClass;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var string
     */
    protected $route;

    /**
     * @var bool
     */
    protected $dynamicRouting;

    protected $securityContext;

    public function __construct($dataClass, $dynamicRouting, $route, RouterInterface $router, $securityContext)
    {
        $this->route = $route;
        $this->dataClass = $dataClass;
        $this->router = $router;
        $this->dynamicRouting = $dynamicRouting;
        $this->securityContext = $securityContext;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $router = $this->router;
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($router) {
            $article = $event->getData();
            $form = $event->getForm();

            if (!empty($article) && $article->getId() && !empty($route)) {
                $url = $router->generate($this->route, array(
                    'id' => $article->getId(),
                    'slug' => $article->getSlug(),
                ), true);

                $form->add('link', 'text', array(
                    'mapped' => false,
                    'data' => $url,
                    'disabled' => true
                ));
            }
        });

        if($this->dynamicRouting) {
            $builder->add('route', 'enhavo_route');
        }

        $builder->add('title', 'text', array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('meta_description', 'textarea', array(
            'label' => 'form.label.meta_description',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('page_title', 'text', array(
            'label' => 'article.form.label.page_title',
            'translation_domain' => 'EnhavoArticleBundle'
        ));

        $builder->add('slug', 'text', array(
            'label' => 'form.label.slug',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('public', 'enhavo_boolean', array(
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('social_media', 'enhavo_boolean', array(
                'label' => 'article.form.label.social_media',
                'translation_domain' => 'EnhavoArticleBundle'
        ));

        $builder->add('priority', 'choice', array(
            'label' => 'article.form.label.priority',
            'translation_domain' => 'EnhavoArticleBundle',
            'choices'   => array(
                '0.1' => '1',
                '0.2' => '2',
                '0.3' => '3',
                '0.4' => '4',
                '0.5' => '5',
                '0.6' => '6',
                '0.7' => '7',
                '0.8' => '8',
                '0.9' => '9',
                '1' => '10'
            ),
            'expanded' => false,
            'multiple' => false
        ));

        $builder->add('change_frequency', 'choice', array(
            'label' => 'article.form.label.change_frequency',
            'translation_domain' => 'EnhavoArticleBundle',
            'choices'   => array(
                'always' => 'article.label.always',
                'hourly' => 'article.label.hourly',
                'daily' => 'article.label.daily',
                'weekly' => 'article.label.weekly',
                'monthly' => 'article.label.monthly',
                'yearly' => 'article.label.yearly',
                'never' => 'article.label.never',
            ),
            'expanded' => false,
            'multiple' => false
        ));

        $builder->add('teaser', 'textarea', array(
            'label' => 'form.label.teaser',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('publication_date', 'datetime', array(
            'label' => 'article.form.label.publication_date',
            'widget' => 'single_text',
            'format' => 'dd.MM.yyyy HH:mm',
            'translation_domain' => 'EnhavoArticleBundle'
        ));

        $builder->add('picture', 'enhavo_files', array(
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false
        ));



        if($this->securityContext->isGranted('WORKFLOW_ACTIVE', $this->dataClass)){
            $entityName = array();
            $entityName[0] = 'article';

            $builder->add('workflow_status', 'enhavo_workflow_status', array(
                'label' => 'workflow.form.label.next_state',
                'translation_domain' => 'EnhavoWorkflowBundle',
                'attr' => $entityName
            ));
        }

        $builder->add('content', 'enhavo_grid');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => $this->dataClass
        ));
    }

    public function getName()
    {
        return 'enhavo_article_article';
    }
}