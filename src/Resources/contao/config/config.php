<?php

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['generatePage'][] = ['terminal42_assets.listener.package', 'onGeneratePage'];

