<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 - Susanne Moog <typo3@susannemoog.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class with utility functions for module menu
 *
 * @author Susanne Moog <typo3@susannemoog.de>
 * @package TYPO3
 * @subpackage core
 */
class Typo3_Utility_BackendModuleUtility {

	/**
	 * @var Typo3_ModuleStorage
	 */
	protected $moduleMenu;

	/**
	 * @var Typo3_Repository_BackendModuleRepository
	 */
	protected $moduleMenuRepository;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->moduleMenu = t3lib_div::makeInstance('Typo3_ModuleStorage');
		$this->moduleMenuRepository = t3lib_div::makeInstance('Typo3_Domain_Repository_BackendModuleRepository');
	}

	/**
	 * This method creates the module menu if necessary
	 * afterwards you only need an instance of Typo3_ModuleStorage
	 * to get the menu
	 *
	 * @return void
	 */
	public function createModuleMenu() {
		if (count($this->moduleMenu->getEntries()) === 0) {
			/** @var $moduleMenu ModuleMenu */
			$moduleMenu = t3lib_div::makeInstance('ModuleMenu');
			$rawData = $moduleMenu->getRawModuleData();
			$this->convertRawModuleDataToModuleMenuObject($rawData);
			$this->createMenuEntriesForTbeModulesExt();
		}
	}

	/**
	 * Creates the module menu object structure from the raw data array
	 *
	 * @see class.modulemenu.php getRawModuleData()
	 * @param array $rawModuleData
	 * @return void
	 */
	protected function convertRawModuleDataToModuleMenuObject(array $rawModuleData) {
		foreach ($rawModuleData as $module) {
			$entry = $this->createEntryFromRawData($module);
			if (isset($module['subitems']) && count($module['subitems']) > 0) {
				foreach ($module['subitems'] as $subitem) {
					$subEntry = $this->createEntryFromRawData($subitem);
					$entry->addChild($subEntry);
				}
			}
			$this->moduleMenu->attachEntry($entry);
		}
	}

	/**
	 * Creates a menu entry object from an array
	 *
	 * @param array $module
	 * @return Typo3_BackendModule
	 */
	protected function createEntryFromRawData(array $module) {
		/** @var $entry Typo3_BackendModule */
		$entry = t3lib_div::makeInstance('Typo3_Domain_Model_BackendModule');
		if (!empty($module['name']) && is_string($module['name'])) {
			$entry->setName($module['name']);
		}
		if (!empty($module['title']) && is_string($module['title'])) {
			$entry->setTitle($GLOBALS['LANG']->sL($module['title']));
		}
		if (!empty($module['onclick']) && is_string($module['onclick'])) {
			$entry->setOnClick($module['onclick']);
		}
		if (!empty($module['link']) && is_string($module['link'])) {
			$entry->setLink($module['link']);
		}
		if (empty($module['link']) && !empty($module['path']) && is_string($module['path'])) {
			$entry->setLink($module['path']);
		}
		if (!empty($module['description']) && is_string($module['description'])) {
			$entry->setDescription($module['description']);
		}
		if (!empty($module['icon']) && is_array($module['icon'])) {
			$entry->setIcon($module['icon']);
		}
		if (!empty($module['navigationComponentId']) && is_string($module['navigationComponentId'])) {
			$entry->setNavigationComponentId($module['navigationComponentId']);
		}
		return $entry;
	}

	/**
	 * Creates the "third level" menu entries (submodules for the info module for example)
	 * from the TBE_MODULES_EXT array
	 *
	 * @return void
	 */
	protected function createMenuEntriesForTbeModulesExt() {
		foreach ($GLOBALS['TBE_MODULES_EXT'] as $mainModule => $tbeModuleExt) {
			list($main) = explode('_', $mainModule);
			$mainEntry = $this->moduleMenuRepository->findByModuleName($main);
			if ($mainEntry !== FALSE) {
				$subEntries = $mainEntry->getChildren();
				if (count($subEntries) > 0) {
					$matchingSubEntry = $this->moduleMenuRepository->findByModuleName($mainModule);
					if ($matchingSubEntry !== FALSE) {
						if (array_key_exists('MOD_MENU', $tbeModuleExt) && array_key_exists('function', $tbeModuleExt['MOD_MENU'])) {
							foreach ($tbeModuleExt['MOD_MENU']['function'] as $subModule) {
								$entry = $this->createEntryFromRawData($subModule);
								$matchingSubEntry->addChild($entry);
							}
						}
					}
				}
			}
		}
	}
}

?>