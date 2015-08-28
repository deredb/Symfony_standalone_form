<?php
include_once 'init.php';

//Form
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;

//form helper
use Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper;
use Symfony\Component\Form\Extension\Templating\TemplatingRendererEngine;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Templating\Helper\AssetsHelper;    
use Symfony\Component\Templating\Helper\SlotsHelper;

//Templating
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\Loader\FilesystemLoader;

//Translator 
use Symfony\Component\Translation\Translator;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\TranslatorHelper;
use Symfony\Component\Translation\Loader\XliffFileLoader;

require_once 'TemplateNameParserLocal.php';

//array of paths for template paths ours stored in main directory 
$loader = new FilesystemLoader(array(basename(__DIR__.'/%name%')));

//instantiate PhpEngine
$phpEngine = new PhpEngine(new TemplateNameParserLocal(), $loader);

//Translator
//create translator
$translator = new Translator('en');
$translator->setFallbackLocales(array('en'));
$translator->addLoader('xlf', new XliffFileLoader());

//dir paths to look for translations
$vendorDir = 'vendor';
    $vendorFormDir = $vendorDir.'/symfony/form';
    $vendorValidatorDir = $vendorDir.'/symfony/validator';
        
//add some built-in translation for the core error messages
$translator->addResource(
    'xlf',
    $vendorFormDir.'/Resources/translations/validators.en.xlf',
    'en',
    'validators'
    );
$translator->addResource(
    'xlf',
    $vendorValidatorDir.'/Resources/translations/validators.en.xlf',
    'en',
    'validators'
    );
//Translator Helper
$translatorHelper = new TranslatorHelper($translator);

//form helper
$defaultThemes = array(realpath(__DIR__.'/vendor/symfony/framework-bundle/Resources/views/Form'));
$rendererEngine = new TemplatingRendererEngine($phpEngine,$defaultThemes);

$formHelper = new FormHelper(new FormRenderer($rendererEngine));

//set helpers
$phpEngine->setHelpers([new AssetsHelper(),new SlotsHelper(),$formHelper,$translatorHelper]);


//form factory 
$formFactory = Forms::createFormFactoryBuilder()
        ->addExtension(new HttpFoundationExtension)->getFormFactory();
//can use this one line to create FormFactory
//$formFactory = Forms::createFormFactory();

//default values for the form field
$defaults = array('dueDate' => new \DateTime('tomorrow'));

//createBuilder creates FormBuilder and calling two methods by chaining
// which returns a Form Ojbect stored under $form variable
$form = $formFactory->createBuilder('form',$defaults)
->add('task', 'text')
->add('dueDate', 'date')
->getForm();

echo $phpEngine->render('simple_form.html.php', array(
    'form' => $form->createView(),
));