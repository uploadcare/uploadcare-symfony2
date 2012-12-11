<?php
namespace Uploadcare\UploadcareBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Uploadcare\UploadcareBundle\UploadcareSymfony;

class UploadcareWidgetType extends AbstractType
{
	/**
	 * @var UploadcareSymfony
	 **/
	private $api = null;
	
	public function __construct($api)
	{
		$this->api = $api;
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