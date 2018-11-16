<?php
/**Stock Chart module
	* @package 			Joomla Stock Chart
	* @subpackage 		Front-end Modules
	* @license    		GNU/GPL, see LICENSE.php
	* @link 			none
	* @author 			Huy Tran

*/

// No direct access
	defined('_JEXEC') or die ;//to make sure this file is being included from Joomla! application

// Include the syndicate functions only once
	require_once dirname(__FILE__) . '/helper.php';
	$historicalData = ModStockChartHelper::getHistoricalData($params->get('symbol'));
	$BcolorData = ModStockChartHelper2::getHistoricalData2($params->get('Bcolor'));
	$HcolorData = ModStockChartHelper3::getHistoricalData3($params->get('Hcolor'));
	require JModuleHelper::getLayoutPath('mod_stockchart');
?>