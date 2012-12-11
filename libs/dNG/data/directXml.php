<?php
//j// BOF

/*n// NOTE
----------------------------------------------------------------------------
XML.php
Multiple XML parsers with a common abstraction layer
----------------------------------------------------------------------------
(C) direct Netware Group - All rights reserved
http://www.direct-netware.de/redirect.php?php;xml

This Source Code Form is subject to the terms of the Mozilla Public License,
v. 2.0. If a copy of the MPL was not distributed with this file, You can
obtain one at http://mozilla.org/MPL/2.0/.
----------------------------------------------------------------------------
http://www.direct-netware.de/redirect.php?licenses;mpl2
----------------------------------------------------------------------------
#echo(phpXmlVersionVersion)#
#echo(__FILEPATH__)#
----------------------------------------------------------------------------
NOTE_END //n*/
/**
* XML (Extensible Markup Language) is the easiest way to use a descriptive
* language for controlling applications locally and world wide.
*
* @internal  We are using ApiGen to automate the documentation process for
*            creating the Developer's Manual. All sections including these
*            special comments will be removed from the release source code.
*            Use the following line to ensure 76 character sizes:
* ----------------------------------------------------------------------------
* @author    direct Netware Group
* @copyright (C) direct Netware Group - All rights reserved
* @package   XML.php
* @since     v0.1.00
* @license   http://www.direct-netware.de/redirect.php?licenses;mpl2
*            Mozilla Public License, v. 2.0
*/
/*#ifdef(PHP5n) */

namespace dNG\data;
/* #\n*/

/* -------------------------------------------------------------------------
All comments will be removed in the "production" packages (they will be in
all development packets)
------------------------------------------------------------------------- */

//j// Functions and classes

/**
* This class extends the bridge between PHP and XML to work with XML and
* create valid documents.
*
* @author    direct Netware Group
* @copyright (C) direct Netware Group - All rights reserved
* @package   XML.php
* @since     v0.1.00
* @license   http://www.direct-netware.de/redirect.php?licenses;mpl2
*            Mozilla Public License, v. 2.0
*/
class directXml extends directXmlParser
{
/* -------------------------------------------------------------------------
Extend the class using old and new behavior
------------------------------------------------------------------------- */

/**
	* Constructor (PHP5+) __construct (directXml)
	*
	* @param string $charset Charset to be added as information to XML output
	* @param integer $timeout_retries Retries before timing out
	* @param object $event_handler EventHandler to use
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __construct($charset = "UTF-8", $timeout_retries = 5, $event_handler = NULL)
	{
		if ($event_handler !== NULL) { $event_handler->debug("#echo(__FILEPATH__)# -Xml->__construct(directXml)- (#echo(__LINE__)#)"); }

/* -------------------------------------------------------------------------
My parent should be on my side to get the work done
------------------------------------------------------------------------- */

		parent::__construct($charset, false, $timeout_retries, $event_handler);
	}
/*#ifdef(PHP4):
/**
	* Constructor (PHP4) directXml
	*
	* @param string $charset Charset to be added as information to XML output
	* @param integer $timeout_retries Retries before timing out
	* @param object $event_handler EventHandler to use
	* @since v0.1.00
*\/
	function directXml($charset = "UTF-8", $timeout_retries = 5, $event_handler = NULL) { $this->__construct($charset, $timeout_retries, $event_handler); }
:#*/
/**
	* Read and convert a simple multi-dimensional array into our XML tree.
	*
	* @param  array $array Input array
	* @param  boolean $overwrite True to overwrite the current
	*         (non-empty) cache
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function arrayImport($array, $overwrite = false)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->arrayImport(+array, +overwrite)- (#echo(__LINE__)#)"); }
		$return = false;

		if (empty($this->data) || $overwrite)
		{
			$array = $this->arrayImportWalker($array);
			$this->data = $array;
			$return = true;
		}

		return $return;
	}

/**
	* Read and convert a single dimension of an array for our XML tree.
	*
	* @param  array &$array Input array
	* @param  integer $level Current level of an multi-dimensional array
	* @return array Output array
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function arrayImportWalker(&$array, $level = 1)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->arrayImportWalker(+array, $level)- (#echo(__LINE__)#)"); }
		$return = array();

		if (is_array($array))
		{
			foreach ($array as $key => $value)
			{
				if (strlen($key))
				{
					if (is_array($value))
					{
						$node_array = array("xml.item" => array("tag" => $key, "level" => $level, "xmlns" => array()));
						$node_array = array_merge($node_array, $this->arrayImportWalker($value, $level + 1));
						$return[$key] = $node_array;
					}
					elseif (!is_object($value)) { $return[$key] = array("tag" => $key, "value" => $value, "xmlns" => array()); }
				}
			}
		}

		return $return;
	}

/**
	* Convert the cached XML tree into a XML string.
	*
	* @param  boolean $flush True to delete the cache content
	* @param  boolean $strict_standard Be standard conform
	* @return string Result string
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function cacheExport($flush = false, $strict_standard = true)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->cacheExport(+flush, +strict_standard)- (#echo(__LINE__)#)"); }

		if (empty($this->data)) { $return = ""; }
		else
		{
			$return = $this->array2xml($this->data, $strict_standard);
			if ($flush) { $this->data = array(); }
		}

		return $return;
	}

/**
	* Set the cache pointer to a specific node.
	*
	* @param  string $node_path Path to the node - delimiter is space
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nodeCachePointer($node_path)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->nodeCachePointer($node_path)- (#echo(__LINE__)#)"); }
		$return = false;

		if (is_string($node_path))
		{
			$node_path = $this->nsTranslatePath($node_path);

			if ($node_path == $this->data_cache_node) { $return = true; }
			else
			{
				$node_ptr =& $this->nodeGetPointer($node_path);

				if ($node_ptr)
				{
					$return = true;
					$this->data_cache_node = $node_path;
					$this->data_cache_ptr =& $node_ptr;
				}
			}
		}

		return $return;
	}

/**
	* Change the attributes of a specified node. Note: XMLNS updates must be
	* handled by the calling code.
	*
	* @param  string $node_path Path to the new node - delimiter is space
	* @param  array $attributes Attributes of the node
	* @return boolean False on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nodeChangeAttributes($node_path, $attributes)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->nodeChangeAttributes($node_path, +attributes)- (#echo(__LINE__)#)"); }
		$return = false;

		if (is_string($node_path) && is_array($attributes))
		{
			$node_path = $this->nsTranslatePath($node_path);
			$node_ptr =& $this->nodeGetPointer($node_path);

			if ($node_ptr)
			{
				if (isset($node_ptr['xml.item'])) { $node_ptr['xml.item']['attributes'] = $attributes; }
				else { $node_ptr['attributes'] = $attributes; }

				$return = true;
			}
		}

		return $return;
	}

/**
	* Change the value of a specified node.
	*
	* @param  string $node_path Path to the new node - delimiter is space
	* @param  string $value Value for the new node
	* @return boolean False on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nodeChangeValue($node_path, $value)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->nodeChangeValue($node_path, +value)- (#echo(__LINE__)#)"); }
		$return = false;

		if (is_string($node_path) && !is_array($value) && !is_object($value))
		{
			$node_path = $this->nsTranslatePath($node_path);
			$node_ptr =& $this->nodeGetPointer($node_path);

			if ($node_ptr)
			{
				if (isset($node_ptr['xml.item'])) { $node_ptr['xml.item']['value'] = $value; }
				else { $node_ptr['value'] = $value; }

				$return = true;
			}
		}

		return $return;
	}

/**
	* Count the occurrence of a specified node.
	*
	* @param  string $node_path Path to the node - delimiter is space
	* @return integer Counted number off matching nodes
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nodeCount($node_path)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->nodeCount($node_path)- (#echo(__LINE__)#)"); }
		$return = 0;

		if (is_string($node_path))
		{
/* -------------------------------------------------------------------------
Get the parent node of the target.
------------------------------------------------------------------------- */

			$node_path = $this->nsTranslatePath($node_path);
			$node_path_array = explode(" ", $node_path);

			if (count($node_path_array) > 1)
			{
				$node_name = array_pop($node_path_array);
				$node_path = implode(" ", $node_path_array);
				$node_ptr =& $this->nodeGetPointer($node_path);
			}
			else
			{
				$node_name = $node_path;
				$node_ptr =& $this->data;
			}

			if ($node_ptr && isset($node_ptr[$node_name])) { $return = (isset($node_ptr[$node_name]['xml.mtree']) ? count($node_ptr[$node_name]) - 1 : 1); }
		}

		return $return;
	}

/**
	* Read a specified node including all children if applicable.
	*
	* @param  string $node_path Path to the node - delimiter is space
	* @param  boolean $remove_metadata False to not remove the xml.item node
	* @return mixed XML node array on success; false on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nodeGet($node_path, $remove_metadata = true)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->nodeGet($node_path, +remove_metadata)- (#echo(__LINE__)#)"); }
		$return = false;

		if (is_string($node_path))
		{
			$node_path = $this->nsTranslatePath($node_path);
			$node_ptr =& $this->nodeGetPointer($node_path);

			if ($node_ptr)
			{
				$return = $node_ptr;
				if ($remove_metadata && isset($return['xml.item'])) { unset($return['xml.item']); }
			}
		}

		return $return;
	}

/**
	* Returns the pointer to a specific node.
	*
	* @param  string $node_path Path to the node - delimiter is space
	* @return mixed XML node pointer on success; false on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function &nodeGetPointer($node_path)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->nodeGetPointer($node_path)- (#echo(__LINE__)#)"); }
		$return = false;

		if (is_string($node_path))
		{
			if (strlen($this->data_cache_node) && strpos($node_path, $this->data_cache_node) === 0)
			{
				$node_path = trim(substr($node_path, strlen($this->data_cache_node)));
				$node_ptr =& $this->data_cache_ptr;
			}
			else { $node_ptr =& $this->data; }

			$is_valid = true;
			$node_path_array = (strlen($node_path) ? explode(" ", $node_path) : array());

			while ($is_valid && !empty($node_path_array))
			{
				$is_valid = false;
				$node_name = array_shift($node_path_array);

				if (preg_match("#^(.+?)\#(\d+)$#", $node_name, $result_array))
				{
					$node_name = $result_array[1];
					$node_position = $result_array[2];
				}
				else { $node_position = -1; }

				if (isset($node_ptr[$node_name]['xml.mtree']))
				{
					if ($node_position >= 0)
					{
						if (isset($node_ptr[$node_name][$node_position]))
						{
							$is_valid = true;
							$node_ptr =& $node_ptr[$node_name][$node_position];
						}
					}
					elseif (isset($node_ptr[$node_name][$node_ptr[$node_name]['xml.mtree']]))
					{
						$is_valid = true;
						$node_ptr =& $node_ptr[$node_name][$node_ptr[$node_name]['xml.mtree']];
					}
				}
				elseif (isset($node_ptr[$node_name]))
				{
					$is_valid = true;
					$node_ptr =& $node_ptr[$node_name];
				}
			}

			if ($is_valid) { $return =& $node_ptr; }
		}

		return $return;
	}

/**
	* Remove a node and all children if applicable.
	*
	* @param  string $node_path Path to the node - delimiter is space
	* @return boolean False on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nodeRemove($node_path)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->nodeRemove($node_path)- (#echo(__LINE__)#)"); }
		$return = false;

		if (is_string($node_path))
		{
/* -------------------------------------------------------------------------
Get the parent node of the target.
------------------------------------------------------------------------- */

			$node_path = $this->nsTranslatePath($node_path);
			$node_path_array = explode(" ", $node_path);

			if (count($node_path_array) > 1)
			{
				$node_name = array_pop($node_path_array);
				$node_path = implode(" ", $node_path_array);
				$node_ptr =& $this->nodeGetPointer($node_path);

				if (strlen($this->data_cache_node) && strpos($node_path, $this->data_cache_node) === 0)
				{
					$this->data_cache_node = "";
					$this->data_cache_ptr =& $this->data;
				}
			}
			else
			{
				$node_name = $node_path;
				$node_ptr =& $this->data;

				$this->data_cache_node = "";
				$this->data_cache_ptr =& $this->data;
			}

			if ($node_ptr)
			{
				if (preg_match("#^(.+?)\#(\d+)$#", $node_name, $result_array))
				{
					$node_name = $result_array[1];
					$node_position = $result_array[2];
				}
				else { $node_position = -1; }

				if (isset($node_ptr[$node_name]['xml.mtree']))
				{
					if ($node_position >= 0)
					{
						if (isset($node_ptr[$node_name][$node_position]))
						{
							unset($node_ptr[$node_name][$node_position]);
							$return = true;
						}
					}
					elseif (isset($node_ptr[$node_name][$node_ptr[$node_name]['xml.mtree']]))
					{
						unset($node_ptr[$node_name][$node_ptr[$node_name]['xml.mtree']]);
						$return = true;
					}

/* -------------------------------------------------------------------------
Update the mtree counter or remove it if applicable.
------------------------------------------------------------------------- */

					if ($return)
					{
						$node_ptr[$node_name]['xml.mtree']--;

						if ($node_ptr[$node_name]['xml.mtree'])
						{
							$node_array = array("xml.mtree" => $node_ptr[$node_name]['xml.mtree']);
							unset($node_ptr[$node_name]['xml.mtree']);
							$node_ptr[$node_name] = array_merge($node_array, array_values($node_ptr[$node_name]));
						}
						else
						{
							unset($node_ptr[$node_name]['xml.mtree']);
							$node_ptr[$node_name] = array_pop($node_ptr[$node_name]);
						}
					}
				}
				elseif (isset($node_ptr[$node_name]))
				{
					unset($node_ptr[$node_name]);
					$return = true;
				}
			}
		}

		return $return;
	}

/**
	* Returns the registered namespace (URI) for a given XML NS or node name
	* containing the registered XML NS.
	*
	* @param  string $input XML NS or node name
	* @return string Namespace (URI)
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nsGetUri($input)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->nsGetUri($input)- (#echo(__LINE__)#)"); }
		$return = "";

		if (preg_match("#^(.+?):(\w+)$#", $input, $result_array))
		{
			if (isset($this->data_ns[$result_array[1]])) { $return = $this->data_ns[$result_array[1]]; }
		}
		elseif (isset($this->data_ns[$input])) { $return = $this->data_ns[$input]; }

		return $return;
	}
}

//j// EOF