<?php
namespace Uploadcare\UploadcareBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Uploadcare\UploadcareBundle\UploadcareSymfony;

class UploadcareWidgetType extends AbstractType
{	
	public function __construct()
	{
	}
	
	public function getDefaultOptions(array $options)
	{
		return array(
			'attr' => array('role' => 'uploadcare-uploader'),
		);
	}

	public function getParent()
	{
		return 'hidden';
	}

	public function getName()
	{
		return 'uploadcare_widget';
	}
}