<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die; 
$option = 'com_joomleague';
?>
<!-- Start Joomleague content -->

<div class="test">
<div id="j-sidebar-container" class="span2">
		<div id="element-box">
			<div class="m">
			<div id="navbar">
			<form action="index.php?option=com_joomleague" method="post" id="adminForm1">
				<div id="area"">
				<?php echo $this->lists['sportstypes']; ?>
				<?php if ($this->sports_type_id): ?>
				<?php echo $this->lists['seasons']; ?><br />
				<?php echo $this->lists['projects']; ?><br />
				<?php endif; ?>
				<?php
				// Project objects
				if ($this->project->id && $this->sports_type_id)
				{
					echo $this->lists['projectteams'];
					?><br /><?php echo $this->lists['projectrounds'];
				}
				?>
				</div>
				<input type="hidden" name="option" value="com_joomleague" />
				<input type="hidden" name="act" value="" id="jl_short_act" />
				<input type="hidden" name="task" value="joomleague.selectws" />
				<?php echo JHtml::_('form.token')."\n"; ?>
			</form>
			<?php
				$n		= 0;
				$tabs	= $this->tabs;
				$link	= $this->link;
				$label	= $this->label;
				$limage	= $this->limage;
				$href	= '';
				$title	= '';
				$image	= '';
				$text	= '';
				
				echo JHtml::_('sliders.start','sliders',array(
												'allowAllClose' => true,
												'startOffset' => $this->active,
												'startTransition' => true,
											true));
				foreach ($tabs as $tab)
				{
					$title=$tab->title;
					echo JHtml::_('sliders.panel',$title,'jfcpanel-panel-'.$tab->name);
					?>
					<div>
						<table class="table"><?php
							for ($i=0; $i<count($link[$n]); $i++)
							{
								$href	= $link[$n][$i];
								$title	= $label[$n][$i];
								$image	= $limage[$n][$i];
								$text	= $label[$n][$i];
								$allowed= true;
								$data 	= JUri::getInstance($href)->getQuery(true);
								$jinput = new JInput($data);
								$task 	= $jinput->getCmd('task');
								//$option = JRequest::getCmd('option');
								if($task != '' && $option == 'com_joomleague')  {
									if (!JFactory::getUser()->authorise($task, 'com_joomleague')) {
										//display the task which is not handled by the access.xml
										//return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR').' Task: '  .$task);
										$allowed = false;
									}
								}
								if($allowed) {
									echo '<tr><td><b><a href="'.$href.'" title="">'.$image.' '.$text.'</a></b></td></tr>';
								} else {
									echo '<tr><td><span title="'.JText::_('JGLOBAL_AUTH_ACCESS_DENIED').'">'.$image.' '.$text.'</span></td></tr>';
								}
							}
							?></table>
					</div>
					<?php
					$n++;
				}
				echo JHtml::_('sliders.end');
				//Extension
				$extensions=JoomleagueHelper::getExtensions(1);
				foreach ($extensions as $e => $extension) {
					$JLGPATH_EXTENSION= JPATH_COMPONENT_SITE.'/extensions/'.$extension;
					$menufile = $JLGPATH_EXTENSION.'/admin/views/joomleague/tmpl/default_'.$extension.'.php';
					if(JFile::exists($menufile )) {
						echo $this->loadTemplate($extension);
					} else {
					}
				}
				?>
			<div class="center"><br />
				<?php
					echo JHtml::_('image','administrator/components/com_joomleague/assets/images/jl.png',JText::_('JoomLeague'),array("title" => JText::_('JoomLeague')));
				?>
			</div>
			</div>
			</div>
			</div>
			
	</div>
	<div id="j-main-container" class="span10">
