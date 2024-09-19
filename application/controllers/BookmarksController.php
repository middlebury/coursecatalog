<?php

/** Zend_Controller_Action */
class BookmarksController extends Zend_Controller_Action
{
    private $bookmarks;

    /**
     * Constructor.
     *
     * @return void
     *
     * @since 11/3/09
     */
    public function init()
    {
        parent::init();

        $this->bookmarks = $this->_helper->bookmarks();

        // Verify our CSRF key
        if (!$this->_getParam('csrf_key') == $this->_helper->csrfKey()) {
            throw new PermissionDeniedException('Invalid CSRF Key. Please log in again.');
        }

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->getResponse()->setHeader('Content-Type', 'text/xml');

        echo '<?xml version="1.0" encoding="UTF-8"?>';
    }

    /**
     * Bookmark a course.
     *
     * @return void
     *
     * @since 7/29/10
     */
    public function addAction()
    {
        $idString = $this->_getParam('course');
        if (!$idString) {
            throw new InvalidArgumentException('No Course Id specified.');
        }
        $id = $this->_helper->osidId->fromString($idString);

        $this->bookmarks->add($id);

        echo '<response>';
        echo '<success/>';
        echo '</response>';
    }

    /**
     * Remove a course bookmark.
     *
     * @return void
     *
     * @since 7/29/10
     */
    public function removeAction()
    {
        $idString = $this->_getParam('course');
        if (!$idString) {
            throw new InvalidArgumentException('No Course Id specified.');
        }
        $id = $this->_helper->osidId->fromString($idString);

        $this->bookmarks->remove($id);

        echo '<response>';
        echo '<success/>';
        echo '</response>';
    }
}
