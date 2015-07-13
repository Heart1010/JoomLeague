<?php defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');

$model = $this->getModel('jlxmlimport');
echo $model->getXml;
?>