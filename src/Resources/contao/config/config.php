<?php

/*
 * Assets Bundle for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2017-2017, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/terminal42/contao-assets
 */

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['generatePage'][] = ['terminal42_assets.listener.assets', 'onGeneratePage'];
