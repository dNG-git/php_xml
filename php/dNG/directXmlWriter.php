<?php
//j// BOF

/*n// NOTE
----------------------------------------------------------------------------
Extended Core: XML
Multiple XML parsers: Common abstraction layer
----------------------------------------------------------------------------
(C) direct Netware Group - All rights reserved
http://www.direct-netware.de/redirect.php?ext_core_xml

This Source Code Form is subject to the terms of the Mozilla Public License,
v. 2.0. If a copy of the MPL was not distributed with this file, You can
obtain one at http://mozilla.org/MPL/2.0/.
----------------------------------------------------------------------------
http://www.direct-netware.de/redirect.php?licenses;mpl2
----------------------------------------------------------------------------
#echo(extCoreXmlVersion)#
extCore_xml/#echo(__FILEPATH__)#
----------------------------------------------------------------------------
NOTE_END //n*/
/**
* XML (Extensible Markup Language) is the easiest way to use a descriptive
* language for controlling applications locally and world wide.
*
* @internal   We are using phpDocumentor to automate the documentation process
*             for creating the Developer's Manual. All sections including
*             these special comments will be removed from the release source
*             code.
*             Use the following line to ensure 76 character sizes:
* ----------------------------------------------------------------------------
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    ext_core
* @subpackage xml
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;mpl2
*             Mozilla Public License, v. 2.0
*/
/*#ifdef(PHP5n) */

namespace dNG;
/* #\n*/

/* -------------------------------------------------------------------------
All comments will be removed in the "production" packages (they will be in
all development packets)
------------------------------------------------------------------------- */

//j// Functions and classes

/* -------------------------------------------------------------------------
Testing for required classes
------------------------------------------------------------------------- */

$g_continue_check = ((defined ("CLASS_directXmlWriter")) ? false : true);
if (!defined ("CLASS_directXmlReader")) { $g_continue_check = false; }

if ($g_continue_check)
{
/**
* This class extends the bridge between PHP and XML to work with XML and
* create valid documents.
*
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    ext_core
* @subpackage xml
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;mpl2
*             Mozilla Public License, v. 2.0
*/
class directXmlWriter extends directXmlReader
{
/* -------------------------------------------------------------------------
Extend the class using old and new behavior
------------------------------------------------------------------------- */

/**
	* Constructor (PHP5+) __construct (directXmlWriter)
	*
	* @param string $f_charset Charset to be added as information to XML output
	* @param integer $f_time Current UNIX timestamp
	* @param integer $f_timeout_count Retries before timing out
	* @param string $f_ext_xml_path Path to the XML parser files.
	* @param boolean $f_debug Debug flag
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __construct ($f_charset = "UTF-8",$f_time = -1,$f_timeout_count = 5,$f_ext_xml_path = "",$f_debug = false)
	{
		if ($f_debug) { $this->debug = array ("xml/#echo(__FILEPATH__)# -Xml->__construct (directXmlWriter)- (#echo(__LINE__)#)"); }

/* -------------------------------------------------------------------------
My parent should be on my side to get the work done
------------------------------------------------------------------------- */

		parent::__construct ($f_charset,false,$f_time,$f_timeout_count,$f_ext_xml_path,$f_debug);
	}
/*#ifdef(PHP4):
/**
	* Constructor (PHP4) directXmlWriter
	*
	* @param string $f_charset Charset to be added as information to XML output
	* @param integer $f_time Current UNIX timestamp
	* @param integer $f_timeout_count Retries before timing out
	* @param string $f_ext_xml_path Path to the XML parser files.
	* @param boolean $f_debug Debug flag
	* @since v0.1.00
*\/
	function directXmlWriter ($f_charset = "UTF-8",$f_time = -1,$f_timeout_count = 5,$f_ext_xml_path = "",$f_debug = false) { $this->__construct ($f_charset,$f_time,$f_timeout_count,$f_ext_xml_path,$f_debug); }
:#*/
/**
	* Read and convert a simple multi-dimensional array into our XML tree.
	*
	* @param  array $f_array Input array
	* @param  boolean $f_overwrite True to overwrite the current
	*         (non-empty) cache
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function arrayImport ($f_array,$f_overwrite = false)
	{
		if ($this->debugging) { $this->debug[] = "xml/#echo(__FILEPATH__)# -Xml->arrayImport (+f_array,+f_overwrite)- (#echo(__LINE__)#)"; }
		$f_return = false;

		if ((empty ($this->data))||($f_overwrite))
		{
			$f_array = $this->arrayImportWalker ($f_array);
			$this->data = $f_array;
			$f_return = true;
		}

		return $f_return;
	}

/**
	* Read and convert a single dimension of an array for our XML tree.
	*
	* @param  array &$f_array Input array
	* @param  integer $f_level Current level of an multi-dimensional array
	* @return array Output array
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function arrayImportWalker (&$f_array,$f_level = 1)
	{
		if ($this->debugging) { $this->debug[] = "xml/#echo(__FILEPATH__)# -Xml->arrayImportWalker (+f_array,$f_level)- (#echo(__LINE__)#)"; }
		$f_return = array ();

		if (is_array ($f_array))
		{
			foreach ($f_array as $f_key => $f_value)
			{
				if (strlen ($f_key))
				{
					if (is_array ($f_value))
					{
						$f_node_array = array ("xml.item" => (array ("tag" => $f_key,"level" => $f_level,"xmlns" => array ())));
						$f_node_array = array_merge ($f_node_array,($this->arrayImportWalker ($f_value,($f_level + 1))));
						$f_return[$f_key] = $f_node_array;
					}
					elseif (!is_object ($f_value)) { $f_return[$f_key] = array ("tag" => $f_key,"value" => $f_value,"xmlns" => array ()); }
				}
			}
		}

		return $f_return;
	}

/**
	* Convert the cached XML tree into a XML string.
	*
	* @param  boolean $f_flush True to delete the cache content
	* @param  boolean $f_strict_standard Be standard conform
	* @return string Result string
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function cacheExport ($f_flush = false,$f_strict_standard = true)
	{
		if ($this->debugging) { $this->debug[] = "xml/#echo(__FILEPATH__)# -Xml->cacheExport (+f_flush,+f_strict_standard)- (#echo(__LINE__)#)"; }

		if (empty ($this->data)) { $f_return = ""; }
		else
		{
			$f_return = $this->array2xml ($this->data,$f_strict_standard);
			if ($f_flush) { $this->data = array (); }
		}

		return $f_return;
	}

/**
	* Set the cache pointer to a specific node.
	*
	* @param  string $f_node_path Path to the node - delimiter is space
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nodeCachePointer ($f_node_path)
	{
		if ($this->debugging) { $this->debug[] = "xml/#echo(__FILEPATH__)# -Xml->nodeCachePointer ($f_node_path)- (#echo(__LINE__)#)"; }
		$f_return = false;

		if (is_string ($f_node_path))
		{
			$f_node_path = $this->nsTranslatePath ($f_node_path);

			if ($f_node_path == $this->data_cache_node) { $f_return = true; }
			else
			{
				$f_node_pointer =& $this->nodeGetPointer ($f_node_path);

				if ($f_node_pointer)
				{
					$f_return = true;
					$this->data_cache_node = $f_node_path;
					$this->data_cache_pointer =& $f_node_pointer;
				}
			}
		}

		return $f_return;
	}

/**
	* Change the attributes of a specified node. Note: XMLNS updates must be
	* handled by the calling code.
	*
	* @param  string $f_node_path Path to the new node - delimiter is space
	* @param  array $f_attributes Attributes of the node
	* @return boolean False on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nodeChangeAttributes ($f_node_path,$f_attributes)
	{
		if ($this->debugging) { $this->debug[] = "xml/#echo(__FILEPATH__)# -Xml->nodeChangeAttributes ($f_node_path,+f_attributes)- (#echo(__LINE__)#)"; }
		$f_return = false;

		if ((is_string ($f_node_path))&&(is_array ($f_attributes)))
		{
			$f_node_path = $this->nsTranslatePath ($f_node_path);
			$f_node_pointer =& $this->nodeGetPointer ($f_node_path);

			if ($f_node_pointer)
			{
				if (isset ($f_node_pointer['xml.item'])) { $f_node_pointer['xml.item']['attributes'] = $f_attributes; }
				else { $f_node_pointer['attributes'] = $f_attributes; }

				$f_return = true;
			}
		}

		return $f_return;
	}

/**
	* Change the value of a specified node.
	*
	* @param  string $f_node_path Path to the new node - delimiter is space
	* @param  string $f_value Value for the new node
	* @return boolean False on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nodeChangeValue ($f_node_path,$f_value)
	{
		if ($this->debugging) { $this->debug[] = "xml/#echo(__FILEPATH__)# -Xml->nodeChangeValue ($f_node_path,+f_value)- (#echo(__LINE__)#)"; }
		$f_return = false;

		if ((is_string ($f_node_path))&&((!is_array ($f_value))&&(!is_object ($f_value))))
		{
			$f_node_path = $this->nsTranslatePath ($f_node_path);
			$f_node_pointer =& $this->nodeGetPointer ($f_node_path);

			if ($f_node_pointer)
			{
				if (isset ($f_node_pointer['xml.item'])) { $f_node_pointer['xml.item']['value'] = $f_value; }
				else { $f_node_pointer['value'] = $f_value; }

				$f_return = true;
			}
		}

		return $f_return;
	}

/**
	* Count the occurrence of a specified node.
	*
	* @param  string $f_node_path Path to the node - delimiter is space
	* @return integer Counted number off matching nodes
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nodeCount ($f_node_path)
	{
		if ($this->debugging) { $this->debug[] = "xml/#echo(__FILEPATH__)# -Xml->nodeCount ($f_node_path)- (#echo(__LINE__)#)"; }
		$f_return = 0;

		if (is_string ($f_node_path))
		{
/* -------------------------------------------------------------------------
Get the parent node of the target.
------------------------------------------------------------------------- */

			$f_node_path = $this->nsTranslatePath ($f_node_path);
			$f_node_path_array = explode (" ",$f_node_path);

			if (count ($f_node_path_array) > 1)
			{
				$f_node_name = array_pop ($f_node_path_array);
				$f_node_path = implode (" ",$f_node_path_array);
				$f_node_pointer =& $this->nodeGetPointer ($f_node_path);
			}
			else
			{
				$f_node_name = $f_node_path;
				$f_node_pointer =& $this->data;
			}

			if (($f_node_pointer)&&(isset ($f_node_pointer[$f_node_name]))) { $f_return = (isset ($f_node_pointer[$f_node_name]['xml.mtree']) ? ((count ($f_node_pointer[$f_node_name])) - 1) : 1); }
		}

		return $f_return;
	}

/**
	* Read a specified node including all children if applicable.
	*
	* @param  string $f_node_path Path to the node - delimiter is space
	* @param  boolean $f_remove_metadata False to not remove the xml.item node
	* @return mixed XML node array on success; false on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nodeGet ($f_node_path,$f_remove_metadata = true)
	{
		if ($this->debugging) { $this->debug[] = "xml/#echo(__FILEPATH__)# -Xml->nodeGet ($f_node_path,+f_remove_metadata)- (#echo(__LINE__)#)"; }
		$f_return = false;

		if (is_string ($f_node_path))
		{
			$f_node_path = $this->nsTranslatePath ($f_node_path);
			$f_node_pointer =& $this->nodeGetPointer ($f_node_path);

			if ($f_node_pointer)
			{
				$f_return = $f_node_pointer;
				if (($f_remove_metadata)&&(isset ($f_return['xml.item']))) { unset ($f_return['xml.item']); }
			}
		}

		return $f_return;
	}

/**
	* Returns the pointer to a specific node.
	*
	* @param  string $f_node_path Path to the node - delimiter is space
	* @return mixed XML node pointer on success; false on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function &nodeGetPointer ($f_node_path)
	{
		if ($this->debugging) { $this->debug[] = "xml/#echo(__FILEPATH__)# -Xml->nodeGetPointer ($f_node_path)- (#echo(__LINE__)#)"; }
		$f_return = false;

		if (is_string ($f_node_path))
		{
			if ((strlen ($this->data_cache_node))&&(strpos ($f_node_path,$this->data_cache_node) === 0))
			{
				$f_node_path = trim (substr ($f_node_path,(strlen ($this->data_cache_node))));
				$f_node_pointer =& $this->data_cache_pointer;
			}
			else { $f_node_pointer =& $this->data; }

			$f_continue_check = true;
			$f_node_path_array = ((strlen ($f_node_path)) ? explode (" ",$f_node_path) : array ());

			while (($f_continue_check)&&(!empty ($f_node_path_array)))
			{
				$f_continue_check = false;
				$f_node_name = array_shift ($f_node_path_array);

				if (preg_match ("#^(.+?)\#(\d+)$#",$f_node_name,$f_result_array))
				{
					$f_node_name = $f_result_array[1];
					$f_node_position = $f_result_array[2];
				}
				else { $f_node_position = -1; }

				if (isset ($f_node_pointer[$f_node_name]['xml.mtree']))
				{
					if ($f_node_position >= 0)
					{
						if (isset ($f_node_pointer[$f_node_name][$f_node_position]))
						{
							$f_continue_check = true;
							$f_node_pointer =& $f_node_pointer[$f_node_name][$f_node_position];
						}
					}
					elseif (isset ($f_node_pointer[$f_node_name][$f_node_pointer[$f_node_name]['xml.mtree']]))
					{
						$f_continue_check = true;
						$f_node_pointer =& $f_node_pointer[$f_node_name][$f_node_pointer[$f_node_name]['xml.mtree']];
					}
				}
				elseif (isset ($f_node_pointer[$f_node_name]))
				{
					$f_continue_check = true;
					$f_node_pointer =& $f_node_pointer[$f_node_name];
				}
			}

			if ($f_continue_check) { $f_return =& $f_node_pointer; }
		}

		return $f_return;
	}

/**
	* Remove a node and all children if applicable.
	*
	* @param  string $f_node_path Path to the node - delimiter is space
	* @return boolean False on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nodeRemove ($f_node_path)
	{
		if ($this->debugging) { $this->debug[] = "xml/#echo(__FILEPATH__)# -Xml->nodeRemove ($f_node_path)- (#echo(__LINE__)#)"; }
		$f_return = false;

		if (is_string ($f_node_path))
		{
/* -------------------------------------------------------------------------
Get the parent node of the target.
------------------------------------------------------------------------- */

			$f_node_path = $this->nsTranslatePath ($f_node_path);
			$f_node_path_array = explode (" ",$f_node_path);

			if (count ($f_node_path_array) > 1)
			{
				$f_node_name = array_pop ($f_node_path_array);
				$f_node_path = implode (" ",$f_node_path_array);
				$f_node_pointer =& $this->nodeGetPointer ($f_node_path);

				if ((strlen ($this->data_cache_node))&&(strpos ($f_node_path,$this->data_cache_node) === 0))
				{
					$this->data_cache_node = "";
					$this->data_cache_pointer =& $this->data;
				}
			}
			else
			{
				$f_node_name = $f_node_path;
				$f_node_pointer =& $this->data;

				$this->data_cache_node = "";
				$this->data_cache_pointer =& $this->data;
			}

			if ($f_node_pointer)
			{
				if (preg_match ("#^(.+?)\#(\d+)$#",$f_node_name,$f_result_array))
				{
					$f_node_name = $f_result_array[1];
					$f_node_position = $f_result_array[2];
				}
				else { $f_node_position = -1; }

				if (isset ($f_node_pointer[$f_node_name]['xml.mtree']))
				{
					if ($f_node_position >= 0)
					{
						if (isset ($f_node_pointer[$f_node_name][$f_node_position]))
						{
							unset ($f_node_pointer[$f_node_name][$f_node_position]);
							$f_return = true;
						}
					}
					elseif (isset ($f_node_pointer[$f_node_name][$f_node_pointer[$f_node_name]['xml.mtree']]))
					{
						unset ($f_node_pointer[$f_node_name][$f_node_pointer[$f_node_name]['xml.mtree']]);
						$f_return = true;
					}

/* -------------------------------------------------------------------------
Update the mtree counter or remove it if applicable.
------------------------------------------------------------------------- */

					if ($f_return)
					{
						$f_node_pointer[$f_node_name]['xml.mtree']--;

						if ($f_node_pointer[$f_node_name]['xml.mtree'])
						{
							$f_node_array = array ("xml.mtree" => $f_node_pointer[$f_node_name]['xml.mtree']);
							unset ($f_node_pointer[$f_node_name]['xml.mtree']);
							$f_node_pointer[$f_node_name] = array_merge ($f_node_array,array_values ($f_node_pointer[$f_node_name]));
						}
						else
						{
							unset ($f_node_pointer[$f_node_name]['xml.mtree']);
							$f_node_pointer[$f_node_name] = array_pop ($f_node_pointer[$f_node_name]);
						}
					}
				}
				elseif (isset ($f_node_pointer[$f_node_name]))
				{
					unset ($f_node_pointer[$f_node_name]);
					$f_return = true;
				}
			}
		}

		return $f_return;
	}

/**
	* Returns the registered namespace (URI) for a given XML NS or node name
	* containing the registered XML NS.
	*
	* @param  string $f_input XML NS or node name
	* @return string Namespace (URI)
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nsGetUri ($f_input)
	{
		if ($this->debugging) { $this->debug[] = "xml/#echo(__FILEPATH__)# -Xml->nsGetUri ($f_input)- (#echo(__LINE__)#)"; }
		$f_return = "";

		if (preg_match ("#^(.+?):(\w+)$#",$f_input,$f_result_array))
		{
			if (isset ($this->data_ns[$f_result_array[1]])) { $f_return = $this->data_ns[$f_result_array[1]]; }
		}
		elseif (isset ($this->data_ns[$f_input])) { $f_return = $this->data_ns[$f_input]; }

		return $f_return;
	}
}

/* -------------------------------------------------------------------------
Mark this class as the most up-to-date one
------------------------------------------------------------------------- */

define ("CLASS_directXmlWriter",true);
}

//j// EOF