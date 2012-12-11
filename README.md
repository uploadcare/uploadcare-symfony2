# Uploadcare Symfony2 Bundle

This is a bundle for [Symfony2][5] to work with [Uploadcare][1]

It's based on a [uploadcare-php][4] library.

## Requirements

- Symfony 2.1+
- PHP 5.3+

## Install

Clone bundle from git to your Symfony/vendor directory:

    git clone git://github.com/uploadcare/uploadcare-symfony2.git vendor/uploadcare
    
Edit your app/autoload.php. Add this:
    
    $loader->add('Uploadcare', __DIR__.'/../vendor/uploadcare/uploadcare/src');
    
Inside your app/config/config.yml add:

    parameters:
        uploadcare.public_key: demopublickey
        uploadcare.secret_key: demoprivatekey

    services:
        uploadcare:
          class: Uploadcare\UploadcareBundle\UploadcareSymfony
          arguments: [%uploadcare.public_key%, %uploadcare.secret_key%]

This will add Uploadcare Bundle as a service.

## Usage

You can access this service inside a controller like this:

    $this->get('uploadcare');
    
It will return a UploadcareSymfony object. This class extends Uploadcare\Api class.

Create some entity:

    namespace UploadcareTest\UploadcareTestBundle\Entity;

    class UCFile
    {
      public $file_id;
    }
    
Create form inside your controller:

    namespace UploadcareTest\UploadcareTestBundle\Controller;
    
    use Uploadcare\UploadcareBundle\Form\Type\UploadcareWidgetType;

    public function indexAction()
    {
        $uc_file = new UCFile();
        $form = $this->createFormBuilder($uc_file)
                     ->add('file_id', new UploadcareWidgetType())
                     ->getForm();
        
        return $this->render('UploadcareTestBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
            'uploadcare' => $this->get('uploadcare'),
        ));
    }
    
You can see a UploadcareWidgetType. It's a hidden field with special parameters to activate widget

To display widget and forms create a view for controller:

    {% extends '::base.html.twig' %}
    {% block body %}
    <form action="{{ path('uploadcare_test_homepage') }}" method="post" {{ form_enctype(form) }}>
        {{ form_widget(form) }}
    
        <input type="submit" />
    </form>
    {% endblock %}
    {% block javascripts %}
      {{ uploadcare.widget.getScriptTag|raw }}
    {% endblock %}
    
Now you should be able to see a widget. Just upload some file and submit form.

To process file edit a controller to look like this:

    public function indexAction()
    {
        $uc_file = new UCFile();
        $form = $this->createFormBuilder($uc_file)
                     ->add('file_id', new UploadcareWidgetType())
                     ->getForm();
        
        $file = null;
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
          $form->bind($request);
        
          if ($form->isValid()) {
            $data = $request->request->get('form');
            $file_id = $data['file_id'];
            $file = $this->get('uploadcare')->getFile($file_id);
            $file->store();
          }
        }
        
        return $this->render('UploadcareTestBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
            'uploadcare' => $this->get('uploadcare'),
            'file' => $file,
        ));
    }
    
The main part is using a "getFile" method to create "$file" and then "store" method on file.

By calling "store" method you told Uploadcare to store file and it will be available at CDN.

You can pass "$file" to the twig template. "$file" is and object of Uploadcare\File. 

With use of this object you can call any operations with file and display the file inside template like this:

    {{ file.resize(400, 400).getImgTag|raw }}

[1]: http://symfony.com/
[2]: https://uploadcare.com/documentation/reference/basic/cdn.html
[3]: https://github.com/uploadcare/uploadcare-wordpress/downloads
[4]: https://github.com/uploadcare/uploadcare-php
[5]: http://wordpress.org/