<?php
/**
 * @version		$Id: default_event.php 837 2012-11-28 17:31:43Z jeffchannell $
 * @package		JCalPro
 * @subpackage	com_jcalpro

**********************************************
JCal Pro
Copyright (c) 2006-2012 Anything-Digital.com
**********************************************
JCalPro is a native Joomla! calendar component for Joomla!

JCal Pro was once a fork of the existing Extcalendar component for Joomla!
(com_extcal_0_9_2_RC4.zip from mamboguru.com).
Extcal (http://sourceforge.net/projects/extcal) was renamed
and adapted to become a Mambo/Joomla! component by
Matthew Friedman, and further modified by David McKinnis
(mamboguru.com) to repair some security holes.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This header must not be removed. Additional contributions/changes
may be added to this header as long as no information is deleted.
**********************************************
Get the latest version of JCal Pro at:
http://anything-digital.com/
**********************************************

 */

defined('JPATH_PLATFORM') or die;

$this->event = $this->item;
$this->document->addStyleSheet(JCalProHelperUrl::media() . '/themes/classic/css/default.css');

$event   = $this->event;
$user    = JFactory::getUser();
$script  = 'document.getElementById(\'%s\').value=\'%s\';document.getElementById(\'%s\').submit();';
$context = 'com_jcalpro.category.' . $event->categories->canonical->id;
$task    = 'event-task-' . $event->id;
$form    = 'event-form-' . $event->id;
$isMine  = ($user->id == $event->created_by && $event->private);
?>
<div class="jcl_subtitlebar">
	<div class="jcl_left"><h1><?php echo JCalProHelperFilter::escape($this->item->title); ?></h1></div>
	<div class="jcl_right">
        <form id="<?php echo $form; ?>" method="post" action="<?php echo JCalProHelperUrl::_(); ?>"><?php

            if ($user->authorise('core.edit', $context) || $user->authorise('core.edit.own', $context) || $isMine) {
                echo JHtml::_('jcalpro.image', 'icon-edit.png', $this->template, array(
                    'title' => JText::_('COM_JCALPRO_EVENT_EDIT_TOOLTIP')
                ,	'onclick' => 'window.location.href=\'' . JCalProHelperUrl::task('event.edit', true, array('id'=>$event->id, 'tmpl' => 'component')) . '\';'
                ,	'style' => 'cursor:pointer;'
                ,	'class' => 'hasTip hasTooltip'
                ));
            }

            if (0 == $event->approved && $user->authorise('core.moderate', $context)) {
                echo JHtml::_('jcalpro.image', 'icon-approve.png', $this->template, array(
                    'title' => JText::_('COM_JCALPRO_EVENT_APPROVE_TOOLTIP')
                ,	'onclick' => sprintf($script, $task, 'events.approve', $form)
                ,	'style' => 'cursor:pointer;'
                ,	'class' => 'hasTip hasTooltip'
                ));
            }

            if (0 == $event->published && $user->authorise('core.edit.state', $context)) {
                echo JHtml::_('jcalpro.image', 'icon-publish.png', $this->template, array(
                    'title' => JText::_('COM_JCALPRO_EVENT_PUBLISH_TOOLTIP')
                ,	'onclick' => sprintf($script, $task, 'events.publish', $form)
                ,	'style' => 'cursor:pointer;'
                ,	'class' => 'hasTip hasTooltip'
                ));
            }

            if ($user->authorise('core.delete', $context) || $isMine) {
                echo JHtml::_('jcalpro.image', 'icon-delete.png', $this->template, array(
                    'title' => JText::_('COM_JCALPRO_EVENT_DELETE_TOOLTIP')
                ,	'onclick' => 'if(confirm(\'' . JText::_('COM_JCALPRO_EVENT_DELETE_CONFIRM') . '\')){'.sprintf($script, $task, 'events.trash', $form).'}'
                ,	'style' => 'cursor:pointer;'
                ,	'class' => 'hasTip hasTooltip'
                ));
            }
            ?>
            <div style="display:none;">
                <input name="cid[]" value="<?php echo $event->id; ?>" />
                <input name="id" value="<?php echo $event->id; ?>" />
                <input name="task" id="event-task-<?php echo $event->id; ?>" value="" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>
    </div>
	<div class="jcl_clear"><!--  --></div>
</div>
<div class="jcl_clear"><!--  --></div>
<div class="jcal_categories">
	<div class="jcl_event_left jcl_left">
		<ul class="jcl_event_categories">
			<li class="jcl_event_categories_canonical">
				<span><?php
					if ($this->raw) :
						echo JCalProHelperFilter::escape($this->item->categories->canonical->title);
					else :
					?>
                                    <?php //echo $this->item->params->get('jcalpro_color'); ?>
                                    <a href="<?php echo JCalProHelperUrl::category($this->item->categories->canonical->id); ?>" class="eventtitle"><?php echo JCalProHelperFilter::escape($this->item->categories->canonical->title); ?></a>
                                        <?php endif; ?>
                                        <?php if (!empty($this->item->categories->categories)) :
						?>,<?php
					endif;
				?></span>
			</li>
			<?php if (!empty($this->item->categories->categories)) : ?>
			<?php foreach ($this->item->categories->categories as $i => $cat) : ?>
			<li>
				<span><?php
					if ($this->raw) :
						echo JCalProHelperFilter::escape($cat->title);
					else :
						?><a href="<?php echo JCalProHelperUrl::category($cat->id); ?>" class="eventtitle"><?php echo JCalProHelperFilter::escape($cat->title); ?></a><?php
					endif;
					if ($i + 1 != count($this->item->categories->categories)) :
						?>,<?php
					endif;
				?></span>
			</li>
			<?php endforeach; ?>
			<?php endif; ?>
		</ul>
	</div>
	<div class="jcl_event_right jcl_right">
		
		<?php if (property_exists($this->item, 'user_end_minidisplay')) : ?>
		<div class="atomic"><span class="label"><?php echo JText::_('COM_JCALPRO_STARTDATE'); ?>:</span> <?php
			echo $this->item->user_minidisplay . ' ';
			if ($this->item->user_minidisplay != $this->item->user_end_minidisplay) :
				echo $this->item->user_start_timedisplay . ' - ' . $this->item->user_end_minidisplay . ' ' . $this->item->user_end_timedisplay;
			else :
				echo $this->item->user_timedisplay;
			endif;
		?></div>
		<?php else: ?>
		<div class="atomic"><span class="label"><?php echo JText::_('COM_JCALPRO_STARTDATE'); ?>:</span> <?php
			echo $this->item->user_minidisplay . ' ' . $this->item->user_timedisplay;
		?></div>
		<?php endif; ?>
	
		<?php if ($this->item->timedisplay != $this->item->user_timedisplay) : ?>
		<div class="atomic">(<?php
		if (property_exists($this->item, 'end_minidisplay')) :
			echo $this->item->minidisplay . ' ';
			if ($this->item->minidisplay != $this->item->end_minidisplay) :
				echo $this->item->start_timedisplay . ' - ' . $this->item->end_minidisplay . ' ' . $this->item->end_timedisplay;
			else :
				echo $this->item->timedisplay;
			endif;
			echo ' ' . $this->item->datetime->format('T');
		else:
			echo $this->item->minidisplay . ' ' . $this->item->timedisplay . ' ' . $this->item->datetime->format('T');
		endif; ?>)</div>
		<?php endif; ?>
		
		<?php if (!empty($this->item->duration_string)) : ?>
		<div class="atomic"><span class="label"><?php echo JText::_('COM_JCALPRO_DURATION'); ?>:</span> <?php echo $this->item->duration_string; ?></div>
		<?php endif; ?>
		<?php if (!empty($this->item->location_data)) : ?>
		<div class="atomic"><span class="label"><?php echo JText::_('COM_JCALPRO_LOCATION'); ?>:</span> <a href="<?php echo JCalProHelperFilter::escape(JCalProHelperUrl::location($this->item->location_data->id)); ?>"><?php echo JCalProHelperFilter::escape($this->item->location_data->title); ?></a></div>
		<?php endif; ?>
		<?php if (!empty($this->item->custom_fields->header)) : foreach ($this->item->custom_fields->header as $field) : if (!empty($this->item->params[$field->name])) : ?>
		<div class="atomic atomic-custom jcl_field_<?php echo JCalProHelperFilter::escape($field->name); ?>"><span class="label"><?php echo JCalProHelperFilter::escape($field->title); ?>:</span> <?php echo JHtml::_('jcalpro.formfieldvalue', $field, $this->item->params[$field->name]); ?></div>
		<?php endif; endforeach; endif; ?>
	</div>
	<div class="jcl_clear"><!--  --></div>
	<?php if (!empty($this->item->custom_fields->top)) : ?>
	<div>
		<?php foreach ($this->item->custom_fields->top as $field) : if (!empty($this->item->params[$field->name])) : ?>
		<div class="atomic atomic-custom jcl_field_<?php echo JCalProHelperFilter::escape($field->name); ?>"><span class="label"><?php echo JCalProHelperFilter::escape($field->title); ?>:</span> <?php echo JHtml::_('jcalpro.formfieldvalue', $field, $this->item->params[$field->name]); ?></div>
		<?php endif; endforeach; ?>
	</div>
	<?php endif; ?>
</div>
<?php if ($this->item->registration) : ?>
<h3 class="jcl_header"><?php echo JText::_('COM_JCALPRO_EVENT_REGISTRATION'); ?></h3>
<div class="jcl_row">
	<div>
		<div class="atomic"><span class="label"><?php echo JText::_('COM_JCALPRO_REGISTRATION_START_DATE'); ?>:</span> <?php
			echo $this->item->registration_data->start_date->format(JText::_('COM_JCALPRO_DATE_FORMAT_MINI_DATE'));
		?></div>
		<div class="atomic"><span class="label"><?php echo JText::_('COM_JCALPRO_REGISTRATION_END_DATE'); ?>:</span> <?php
			echo $this->item->registration_data->end_date->format(JText::_('COM_JCALPRO_DATE_FORMAT_MINI_DATE'));
		?></div>
		<?php if ($this->item->registration_capacity) : ?>
		<div class="atomic"><span class="label"><?php echo JText::_('COM_JCALPRO_REGISTRATION_CAPACITY'); ?>:</span> <?php
			echo JText::sprintf('COM_JCALPRO_REGISTRATION_CAPACITY_DISPLAY', $this->item->registration_capacity);
		?></div>
		<?php endif; ?>
		<?php if ($this->item->registration_data->can_register) : ?>
		<div>
			<a href="<?php echo JCalProHelperUrl::task('registration.add', true, array('event_id' => $this->item->id)); ?>"><?php echo JText::_('COM_JCALPRO_MAINMENU_REGISTER'); ?></a>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>
<div class="jcl_row">
	<div class="jcl_event_body jcl_nocolor">
		<?php if (!empty($this->item->custom_fields->side)) : ?>
		<div class="eventdescright">
			
			<div class="eventdescright_custom">
				<?php foreach ($this->item->custom_fields->side as $field) : if (!empty($this->item->params[$field->name])) : ?>
				<div class="atomic atomic-custom jcl_field_<?php echo JCalProHelperFilter::escape($field->name); ?>"><span class="label"><?php echo JCalProHelperFilter::escape($field->title); ?>:</span> <?php echo JHtml::_('jcalpro.formfieldvalue', $field, $this->item->params[$field->name]); ?></div>
				<?php endif; endforeach; ?>
			</div>
			
			
		</div>
		<?php endif; ?>
		<div class="eventdesclarge"><?php echo $this->item->description; ?></div>
		<div class="jcl_clear"><!--  --></div>
		<?php if (!empty($this->item->custom_fields->bottom)) : foreach ($this->item->custom_fields->bottom as $field) : if (!empty($this->item->params[$field->name])) : ?>
		<div class="atomic atomic-custom jcl_field_<?php echo JCalProHelperFilter::escape($field->name); ?>"><span class="label"><?php echo JCalProHelperFilter::escape($field->title); ?>:</span> <?php echo JHtml::_('jcalpro.formfieldvalue', $field, $this->item->params[$field->name]); ?></div>
		<?php endif; endforeach; endif; ?>
	</div>
</div>
