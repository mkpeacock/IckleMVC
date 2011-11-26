<?php
namespace IckleMVC\Views;
/**
 * Administrative: List pages view
 * @author Michael Peacock
 */
class Administration_Content_Pages_List extends View {
	
	/**
	 * Generate the output
	 * @param mixed $model
	 * @return void
	 */
	public function generate( $model )
	{
		$this->registry->getObject('template')->buildFromTemplates( 'header.tpl.php', 'footer.tpl.php');
		
	}
	
	
	
}



?>