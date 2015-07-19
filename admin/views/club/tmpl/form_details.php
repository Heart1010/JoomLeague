<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
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