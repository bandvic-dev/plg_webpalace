<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2023 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

//no direct accees
defined('_JEXEC') or die('Restricted Aceess');


SpAddonsConfig::addonConfig(
	array(
		'type'=>'content',
		'addon_name'=>'telegram',
		'title'=> 'Telegram Addon',
		'desc'=> 'Telegram addon for SP Page Builder',
		'icon'=>JURI::root() . 'plugins/sppagebuilder/webpalace/addons/telegram/assets/images/icon.png',
		'category'=>'WebPalace Addons',
		'attr'=>array(
			'general' => array(
				'admin_label'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL_DESC'),
					'std'=> ''
				),
				// Title
				'title'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_DESC'),
					'std'=>  'This is sample title'
				),

				'heading_selector'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_DESC'),
					'values'=>array(
						'h1'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H1'),
						'h2'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H2'),
						'h3'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H3'),
						'h4'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H4'),
						'h5'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H5'),
						'h6'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H6'),
						'p'=>	'p',
						'span'=> 'span',
						'div'=> 'div'
					),
					'std'=>'h3',
					'depends'=>array(array('title', '!=', '')),
				),

				'api_token'=>array(
					'type'=>'text',
					'title'=>'Token',
					'desc' =>'Add token from chatbot @BotFather',
					'std'=>'',
				),

				'type'=>array(
					'type'=>'select',
					'title'=>'Type',
					'desc'=>'Type of posts',
					'values'=>array(
						'is_channel'=>'Channel',
					),
					'std'=>'',
				),

				'channel_username'=>array(
					'type'=>'text',
					'title'=>'Channel Name',
					'desc'=>'Add your channel name here',
					'std'=>'',
					'depends'=>array(array('type', '!=', 'is_group')),
				),

				'posts_count'=>array(
					'type'=>'number',
					'title'=>'Posts Count',
					'desc' =>'Add post count to show',
					'std'=>'3',
				),

				'bottom_desc'=>array(
					'type'=>'text',
					'title'=>'Bottom description',
					'desc'=>'Add bottom description',
					'std'=>  'Be trendy'
				),

				'class'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS_DESC'),
					'std'=>''
				),

			),
		),
	)
);