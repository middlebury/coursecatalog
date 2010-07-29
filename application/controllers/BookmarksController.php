<?php

/** Zend_Controller_Action */
class BookmarksController extends Zend_Controller_Action
{
	
	private $bookmarks;
	
	/**
	 * Constructor
	 * 
	 * @return void
	 * @access public
	 * @since 11/3/09
	 */
	public function init () {
		parent::init();
		
		// Initialize our Model
		if (!$this->_helper->auth->getHelper()->isAuthenticated())
			throw new Exception('You must be logged in to perform this action.');
		$this->bookmarks = new Bookmarks(Zend_Registry::get('db'),  $this->_helper->auth->getHelper()->getUserId());
		
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
		
		print '<'.'?xml version="1.0" encoding="UTF-8"?'.'>';
	}
	
	/**
	 * Bookmark a course
	 * 
	 * @return void
	 * @access public
	 * @since 7/29/10
	 */
	public function addAction () {
		$idString = $this->_getParam('course');
		if (!$idString)
			throw new InvalidArgumentException('No Course Id specified.');
		$id = $this->_helper->osidId->fromString($idString);
		
		$this->bookmarks->add($id);
		
		print '<response>';
		print '<success/>';
		print '</response>';
		
	}
	
	/**
	 * Remove a course bookmark
	 * 
	 * @return void
	 * @access public
	 * @since 7/29/10
	 */
	public function removeAction () {
		$idString = $this->_getParam('course');
		if (!$idString)
			throw new InvalidArgumentException('No Course Id specified.');
		$id = $this->_helper->osidId->fromString($idString);
		
		$this->bookmarks->remove($id);
		
		print '<response>';
		print '<success/>';
		print '</response>';
		
	}
}
