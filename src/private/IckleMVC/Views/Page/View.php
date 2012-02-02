<?php
namespace IckleMVC\Views;
class Page_View extends AbstractView{
	
	
	public function generate( $model=null )
	{
		$this->registry->getObject('template')->dataToTags( $model->getData(), 'page_' );
		$this->registry->getObject('template')->getPage()->setTitle( $model->getTitle() );
		// display the page
		$templateObject = $this->registry->getObject('template');
		call_user_func_array(array( $templateObject, "buildFromTemplates"), $model->getTemplateFiles() );
			
		//$this->registry->getObject('template')->buildFromTemplates( 'header.tpl.php', 'main.tpl.php', 'footer.tpl.php' );
		$this->registry->getObject('frontmenu')->buildMenu( $model->getContentId() );
		$this->output();
	}
	
}