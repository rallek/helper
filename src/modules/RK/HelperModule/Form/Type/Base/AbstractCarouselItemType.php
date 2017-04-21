<?php
/**
 * Helper.
 *
 * @copyright Ralf Koester (RK)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://k62.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace RK\HelperModule\Form\Type\Base;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\SettingsModule\Api\LocaleApi;
use RK\HelperModule\Entity\Factory\HelperFactory;
use RK\HelperModule\Helper\FeatureActivationHelper;
use RK\HelperModule\Helper\ListEntriesHelper;

/**
 * Carousel item editing form type base class.
 */
abstract class AbstractCarouselItemType extends AbstractType
{
    use TranslatorTrait;

    /**
     * @var HelperFactory
     */
    protected $entityFactory;

    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;

    /**
     * @var LocaleApi
     */
    protected $localeApi;

    /**
     * @var FeatureActivationHelper
     */
    protected $featureActivationHelper;

    /**
     * CarouselItemType constructor.
     *
     * @param TranslatorInterface $translator    Translator service instance
     * @param HelperFactory        $entityFactory Entity factory service instance
     * @param ListEntriesHelper   $listHelper    ListEntriesHelper service instance
     * @param LocaleApi            $localeApi     LocaleApi service instance
     * @param FeatureActivationHelper $featureActivationHelper FeatureActivationHelper service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        HelperFactory $entityFactory,
        ListEntriesHelper $listHelper,
        LocaleApi $localeApi,
        FeatureActivationHelper $featureActivationHelper
    ) {
        $this->setTranslator($translator);
        $this->entityFactory = $entityFactory;
        $this->listHelper = $listHelper;
        $this->localeApi = $localeApi;
        $this->featureActivationHelper = $featureActivationHelper;
    }

    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(/*TranslatorInterface */$translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addEntityFields($builder, $options);
        $this->addIncomingRelationshipFields($builder, $options);
        $this->addModerationFields($builder, $options);
        $this->addReturnControlField($builder, $options);
        $this->addSubmitButtons($builder, $options);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $entity = $event->getData();
            foreach (['itemImage'] as $uploadFieldName) {
                $entity[$uploadFieldName] = [
                    $uploadFieldName => $entity[$uploadFieldName] instanceof File ? $entity[$uploadFieldName]->getPathname() : null
                ];
            }
        });
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $entity = $event->getData();
            foreach (['itemImage'] as $uploadFieldName) {
                if (is_array($entity[$uploadFieldName])) {
                    $entity[$uploadFieldName] = $entity[$uploadFieldName][$uploadFieldName];
                }
            }
        });
    }

    /**
     * Adds basic entity fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addEntityFields(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('itemName', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('Item name') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('the item name will not be shown. it is just for notification in the backend')
            ],
            'help' => $this->__('the item name will not be shown. it is just for notification in the backend'),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the item name of the carousel item')
            ],
            'required' => true,
        ]);
        
        $builder->add('title', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('Title') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the title of the carousel item')
            ],
            'required' => false,
        ]);
        
        $builder->add('subtitle', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('Subtitle') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the subtitle of the carousel item')
            ],
            'required' => false,
        ]);
        
        $builder->add('link', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('Link') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Please be carfull with the link. The link is not validated!')
            ],
            'help' => $this->__('Please be carfull with the link. The link is not validated!'),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the link of the carousel item')
            ],
            'required' => false,
        ]);
        
        $builder->add('itemImage', 'RK\HelperModule\Form\Type\Field\UploadType', [
            'label' => $this->__('Item image') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('The ration of the image must be 3:1. Please format before uploading. The image will be shrinked to 1800px width. Your image should be not much less than this.')
            ],
            'help' => $this->__('The ration of the image must be 3:1. Please format before uploading. The image will be shrinked to 1800px width. Your image should be not much less than this.'),
            'attr' => [
                'class' => ' validate-upload',
                'title' => $this->__('Enter the item image of the carousel item')
            ],
            'required' => false && $options['mode'] == 'create',
            'entity' => $options['entity'],
            'allowed_extensions' => 'gif, jpeg, jpg, png',
            'allowed_size' => ''
        ]);
        
        $builder->add('titleColor', 'RK\HelperModule\Form\Type\Field\ColourType', [
            'label' => $this->__('Title color') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('select white first. If this do not fit try a grey or black.')
            ],
            'help' => $this->__('select white first. If this do not fit try a grey or black.'),
            'empty_data' => '#ffffff',
            'attr' => [
                'maxlength' => 255,
                'class' => ' validate-nospace validate-htmlcolour rkhelpermoduleColourPicker',
                'title' => $this->__('Choose the title color of the carousel item')
            ],
            'required' => false,
        ]);
        
        $builder->add('itemStartDate', 'Symfony\Component\Form\Extension\Core\Type\DateType', [
            'label' => $this->__('Item start date') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('if you do not enter a date the date of today will be used')
            ],
            'help' => $this->__('if you do not enter a date the date of today will be used'),
            'empty_data' => '',
            'attr' => [
                'class' => ' validate-daterange-carouselitem',
                'title' => $this->__('Enter the item start date of the carousel item')
            ],
            'required' => false,
            'empty_data' => null,
            'widget' => 'single_text'
        ]);
        
        $builder->add('intemEndDate', 'Symfony\Component\Form\Extension\Core\Type\DateType', [
            'label' => $this->__('Intem end date') . ':',
            'help' => $this->__('Note: this value must be in the future.'),
            'empty_data' => '2099-12-31',
            'attr' => [
                'class' => ' validate-date-future validate-daterange-carouselitem',
                'title' => $this->__('Enter the intem end date of the carousel item')
            ],
            'required' => true,
            'empty_data' => '2099-12-31',
            'widget' => 'single_text'
        ]);
        
        $builder->add('singleItemIdentifier', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('Single item identifier') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Here we can filter one single item in the block advanced filtering to reuse an image. Be shure the itemEndDate is valid and not in the past.')
            ],
            'help' => $this->__('Here we can filter one single item in the block advanced filtering to reuse an image. Be shure the itemEndDate is valid and not in the past.'),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => 'validate-unique',
                'title' => $this->__('Enter the single item identifier of the carousel item')
            ],
            'required' => false,
        ]);
        
        $builder->add('linkExternal', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', [
            'label' => $this->__('Link external') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('if this is selected the link will open another window or tab')
            ],
            'help' => $this->__('if this is selected the link will open another window or tab'),
            'attr' => [
                'class' => '',
                'title' => $this->__('link external ?')
            ],
            'required' => false,
        ]);
        
        $builder->add('itemLocale', 'Zikula\Bundle\FormExtensionBundle\Form\Type\LocaleType', [
            'label' => $this->__('Item locale') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => ' validate-nospace',
                'title' => $this->__('Choose the item locale of the carousel item')
            ],
            'required' => false,
            'placeholder' => $this->__('All'),
            'choices' => $this->localeApi->getSupportedLocaleNames(),
            'choices_as_values' => true
        ]);
    }

    /**
     * Adds fields for incoming relationships.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addIncomingRelationshipFields(FormBuilderInterface $builder, array $options)
    {
        $queryBuilder = function(EntityRepository $er) {
            // select without joins
            return $er->getListQueryBuilder('', '', false);
        };
        $builder->add('carousel', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
            'class' => 'RKHelperModule:CarouselEntity',
            'choice_label' => 'getTitleFromDisplayPattern',
            'multiple' => false,
            'expanded' => false,
            'query_builder' => $queryBuilder,
            'placeholder' => $this->__('Please choose an option'),
            'required' => false,
            'label' => $this->__('Carousel'),
            'attr' => [
                'title' => $this->__('Choose the carousel')
            ]
        ]);
    }

    /**
     * Adds special fields for moderators.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addModerationFields(FormBuilderInterface $builder, array $options)
    {
        if (!$options['has_moderate_permission']) {
            return;
        }
    
        $builder->add('moderationSpecificCreator', 'RK\HelperModule\Form\Type\Field\UserType', [
            'mapped' => false,
            'label' => $this->__('Creator') . ':',
            'attr' => [
                'maxlength' => 11,
                'class' => ' validate-digits',
                'title' => $this->__('Here you can choose a user which will be set as creator')
            ],
            'empty_data' => 0,
            'required' => false,
            'help' => $this->__('Here you can choose a user which will be set as creator')
        ]);
        $builder->add('moderationSpecificCreationDate', 'Symfony\Component\Form\Extension\Core\Type\DateTimeType', [
            'mapped' => false,
            'label' => $this->__('Creation date') . ':',
            'attr' => [
                'class' => '',
                'title' => $this->__('Here you can choose a custom creation date')
            ],
            'empty_data' => '',
            'required' => false,
            'with_seconds' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'help' => $this->__('Here you can choose a custom creation date')
        ]);
    }

    /**
     * Adds the return control field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addReturnControlField(FormBuilderInterface $builder, array $options)
    {
        if ($options['mode'] != 'create') {
            return;
        }
        $builder->add('repeatCreation', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', [
            'mapped' => false,
            'label' => $this->__('Create another item after save'),
            'required' => false
        ]);
    }

    /**
     * Adds submit buttons.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSubmitButtons(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['actions'] as $action) {
            $builder->add($action['id'], 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
                'label' => $this->__(/** @Ignore */$action['title']),
                'icon' => ($action['id'] == 'delete' ? 'fa-trash-o' : ''),
                'attr' => [
                    'class' => $action['buttonClass'],
                    'title' => $this->__(/** @Ignore */$action['description'])
                ]
            ]);
        }
        $builder->add('reset', 'Symfony\Component\Form\Extension\Core\Type\ResetType', [
            'label' => $this->__('Reset'),
            'icon' => 'fa-refresh',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
        $builder->add('cancel', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
            'label' => $this->__('Cancel'),
            'icon' => 'fa-times',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'rkhelpermodule_carouselitem';
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                // define class for underlying data (required for embedding forms)
                'data_class' => 'RK\HelperModule\Entity\CarouselItemEntity',
                'empty_data' => function (FormInterface $form) {
                    return $this->entityFactory->createCarouselItem();
                },
                'error_mapping' => [
                    'itemImage' => 'itemImage.itemImage',
                    'isItemStartDateBeforeIntemEndDate' => 'itemStartDate',
                ],
                'mode' => 'create',
                'actions' => [],
                'has_moderate_permission' => false,
                'filter_by_ownership' => true,
                'inline_usage' => false
            ])
            ->setRequired(['entity', 'mode', 'actions'])
            ->setAllowedTypes([
                'mode' => 'string',
                'actions' => 'array',
                'has_moderate_permission' => 'bool',
                'filter_by_ownership' => 'bool',
                'inline_usage' => 'bool'
            ])
            ->setAllowedValues([
                'mode' => ['create', 'edit']
            ])
        ;
    }
}
