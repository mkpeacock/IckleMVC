<?php
namespace IckleMVC\Views;
class Blog_View extends AbstractView{
	
	
	public function generate( $model=null )
	{
		$this->registry->getObject('template')->dataToTags( $model->getData(), 'page_' );
		$this->registry->getObject('template')->getPage()->setTitle( $model->getTitle() );
		

		// display the page		
		$this->registry->getObject('template')->buildFromTemplates( 'header.tpl.php', 'blog/view.tpl.php', 'footer.tpl.php' );
		
		//$this->registry->getObject('frontmenu')->buildMenu( $model->getContentId() );
		$this->output();
	}
	
	private function generateCommentsForm( $model )
	{
		// can users post comments for this entry?
		if( $model->getCommentsEnabled() == true )
		{
				
		}
		else
		{
				
		}
	}
	
	private function generateCommentsList( $model )
	{
		// are there comments for this entry?
	}
	
}