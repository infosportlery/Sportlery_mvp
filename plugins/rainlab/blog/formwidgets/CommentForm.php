<?php namespace Rainlab\Blog\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * CommentForm Form Widget
 */
class CommentForm extends FormWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'rainlab_blog_comment_form';

    /**
     * @inheritDoc
     */
    public function init()
    {
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('commentform');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        $this->addCss('css/commentform.css', 'rainlab.blog');
        $this->addJs('js/commentform.js', 'rainlab.blog');
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
