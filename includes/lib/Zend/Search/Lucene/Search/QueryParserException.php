<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Search
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: QueryParserException.php 16971 2009-07-22 18:05:45Z mikaelkael $
 */


/**
 * Zend_Search_Lucene base exception
 */
require_once ABSPATH.'wp-content/plugins/'.Basename_Dirname_AOS.'/includes/lib/Zend/Search/Lucene/Exception.php';


/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Search
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * Special exception type, which may be used to intercept wrong user input
 */
class Zend_Search_Lucene_Search_QueryParserException extends Zend_Search_Lucene_Exception
{}

