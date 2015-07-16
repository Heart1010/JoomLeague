<?php 
/**
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license		GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die;
?>

<fieldset class="form-horizontal">
			
	<?php 
		echo $this->form->renderField('id');
		echo $this->form->renderField('name');
		echo $this->form->renderField('alias');
		
		if (!$this->edit): 
	?>
		<div class="control-group">
			<div class="control-label"><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_CLUB_CREATE_TEAM')?></div>
			<div class="controls"><input type="checkbox" name="createTeam" /></div>
		</div>
	<?php 
		endif;

		echo $this->form->renderField('admin');
		echo $this->form->renderField('address');
		echo $this->form->renderField('zipcode');
		echo $this->form->renderField('location');
		echo $this->form->renderField('state');
		echo $this->form->renderField('country');
		echo $this->form->renderField('phone');
		echo $this->form->renderField('fax');
		echo $this->form->renderField('email');
		echo $this->form->renderField('website');
		echo $this->form->renderField('manager');
		echo $this->form->renderField('president');
		echo $this->form->renderField('founded');
		echo $this->form->renderField('dissolved');
		echo $this->form->renderField('standard_playground');
	?>		
</fieldset>