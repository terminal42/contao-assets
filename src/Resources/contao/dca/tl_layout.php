<?php

/**
 * Extend palettes
 */
\Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('assets_legend', 'webfonts_legend', \Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_BEFORE)
    ->addField('assets_package', 'assets_legend', \Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_layout');

/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_layout']['fields']['assets_package'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_layout']['assets_package'],
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => ['terminal42_assets.listener.package', 'onDcaOptionsCallback'],
    'eval'             => ['includeBlankOption' => true, 'tl_class' => 'w50'],
    'sql'              => ['type' => 'string', 'length' => 128],
];
