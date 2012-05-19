<?php
namespace IckleMVC\Pages\Views;
use IckleMVC\Core\AbstractView;
class View extends AbstractView{
	
	
	public function generate( $model=null )
	{
		$this->templateEngine->dataToTags( $model->getData(), 'page_' );
		$this->templateEngine->getPage()->setTitle( $model->getTitle() );
		// display the page
		$templateObject = $this->templateEngine;
		call_user_func_array(array( $templateObject, "buildFromTemplates"), $model->getTemplateFiles() );
			
		//$this->registry->getObject('template')->buildFromTemplates( 'header.tpl.php', 'main.tpl.php', 'footer.tpl.php' );
		$this->frontMenu->buildMenu( $model->getContentId() );
		$this->output();
	}
	
}