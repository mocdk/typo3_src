<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Georg Ringer <typo3@ringerge.org>
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
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Render the icon of a report
 *
 * @package TYPO3
 * @subpackage tx_reports
 */
class Tx_Reports_ViewHelpers_IconViewHelper extends Tx_Fluid_ViewHelpers_Be_AbstractBackendViewHelper {

	/**
	 * Renders the icon
	 *
	 * @param string $icon Icon to be used
	 * @param string $title Optional title
	 * @return strin Content rendered image
	 */
	public function render($icon, $title = '') {
		if (!empty($icon)) {
			$absIconPath = t3lib_div::getFileAbsFilename($icon);

			if (file_exists($absIconPath)) {
				$icon = $GLOBALS['BACK_PATH'] . '../' . str_replace(PATH_site, '', $absIconPath);
			}
		} else {
			$icon = t3lib_extMgm::extRelPath('reports') . 'Resources/Public/moduleicon.gif';
		}

		$content = '<img' . t3lib_iconworks::skinImg($GLOBALS['BACK_PATH'], $icon, 'width="16" height="16"') .
			' title="' . htmlspecialchars($title) . '" alt="' . htmlspecialchars($title) . '" />';

		return $content;
	}

}
?>