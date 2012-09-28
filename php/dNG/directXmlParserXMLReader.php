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

use \XMLReader;
/* #\n*/

/* -------------------------------------------------------------------------
All comments will be removed in the "production" packages (they will be in
all development packets)
------------------------------------------------------------------------- */

//j// Functions and classes

if (!defined ("CLASS_directXmlParserXMLReader"))
{
/**
* This implementation supports XMLReader for XML parsing.
*
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    ext_core
* @subpackage xml
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;mpl2
*             Mozilla Public License, v. 2.0
*/
class directXmlParserXMLReader
{
/**
	* @var array $debug Debug message container
*/
	/*#ifndef(PHP4) */public/* #*//*#ifdef(PHP4):var:#*/ $debug;
/**
	* @var boolean $debugging True if we should fill the debug message
	*      container
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $debugging;
/**
	* @var array $node_types Node types that this parser knows
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $node_types;
/**
	* @var direct_xml_reader $parser Container for the XML document
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $parser;
/**
	* @var integer $time Current UNIX timestamp
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $time;
/**
	* @var integer $timeout_count Retries before timing out
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $timeout_count;
/**
	* @var boolean $PHP_is_valid True if the "is_valid ()" method is available
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $PHP_is_valid;

/* -------------------------------------------------------------------------
Construct the class using old and new behavior
------------------------------------------------------------------------- */

/**
	* Constructor (PHP5+) __construct (directXmlParserXMLReader)
	*
	* @param direct_xml_reader &$f_parser Container for the XML document
	* @param integer $f_time Current UNIX timestamp
	* @param integer $f_timeout_count Retries before timing out
	* @param boolean $f_debug Debug flag
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __construct (&$f_parser,$f_time = -1,$f_timeout_count = 5,$f_debug = false)
	{
		$this->debugging = $f_debug;
		if ($this->debugging) { $this->debug = array ("directXmlParserXMLReader/#echo(__FILEPATH__)# -XmlParser->__construct (directXmlParserXMLReader)- (#echo(__LINE__)#)"); }

/* -------------------------------------------------------------------------
Connect to the PHP container for the XML document 
------------------------------------------------------------------------- */

		$this->parser = $f_parser;
		$this->time = (($f_time < 0) ? time () : $f_time);
		$this->timeout_count = $f_timeout_count;

		if (defined ("XMLREADER_ELEMENT"))
		{
$this->node_types = array (
"attribute" => XMLREADER_ATTRIBUTE,
"cdata" => XMLREADER_CDATA,
"comment" => XMLREADER_COMMENT,
"element" => XMLREADER_ELEMENT,
"element_end" => XMLREADER_END_ELEMENT,
"text" => XMLREADER_TEXT,
"whitespace" => XMLREADER_WHITESPACE,
"whitespace_significant" => XMLREADER_SIGNIFICANT_WHITESPACE
);
		}
		else
		{
$this->node_types = array (
"attribute" => XMLReader::ATTRIBUTE,
"cdata" => XMLReader::CDATA,
"comment" => XMLReader::COMMENT,
"element" => XMLReader::ELEMENT,
"element_end" => XMLReader::END_ELEMENT,
"text" => XMLReader::TEXT,
"whitespace" => XMLReader::WHITESPACE,
"whitespace_significant" => XMLReader::SIGNIFICANT_WHITESPACE
);
		}
	}
/*#ifdef(PHP4):
/**
	* Constructor (PHP4) directXmlParserXMLReader
	*
	* @param direct_xml_reader &$f_parser Container for the XML document
	* @param integer $f_time Current UNIX timestamp
	* @param integer $f_timeout_count Retries before timing out
	* @param boolean $f_debug Debug flag
	* @since v0.1.00
*\/
	function directXmlParserXMLReader (&$f_parser,$f_time = -1,$f_timeout_count = 5,$f_debug = false) { $this->__construct ($f_parser,$f_time,$f_timeout_count,$f_debug); }
:#\n*/
/**
	* Destructor (PHP5+) __destruct (directXmlParserXMLReader)
	*
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __destruct () { $this->parser = NULL; }

/**
	* Converts XML data into a multi-dimensional array ... using the
	* "simplexml_load_string ()" result.
	*
	* @param  object &$f_xmlreader SimpleXMLElement object
	* @param  boolean $f_strict_standard Be standard conform
	* @return array Multi-dimensional XML tree
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2arrayXMLReader (&$f_xmlreader,$f_strict_standard = true)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserXMLReader/#echo(__FILEPATH__)# -XmlParser->xml2arrayXMLReader (+f_xmlreader,+f_strict_standard)- (#echo(__LINE__)#)"; }
		$f_return = array ();

		if ($this->xml2arrayXMLReaderIsValid ($f_xmlreader))
		{
			$f_continue_check = true;
			$f_timeout_time = ($this->time + $this->timeout_count);
			$this->parser->set (array ());

			do { $f_continue_check = $f_xmlreader->read (); }
			while (($f_continue_check)&&($f_xmlreader->nodeType != $this->node_types['element'])&&($f_timeout_time > (time ())));

			$f_xmlreader_array = $this->xml2arrayXMLReaderWalker ($f_xmlreader,$f_strict_standard);
			$f_xmlreader->close ();

			if ($f_xmlreader_array) { $f_continue_check = $this->xml2arrayXMLReaderArrayWalker ($f_xmlreader_array,$f_strict_standard); }
			if ($f_continue_check) { $f_return = $this->parser->get (); }
		}

		return $f_return;
	}

/**
	* Imports a pre-parsed XML array into the given parser instance.
	*
	* @param  array &$f_data Result array of a "xml2arrayXMLReaderWalker ()"
	* @param  boolean $f_strict_standard Be standard conform
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function xml2arrayXMLReaderArrayWalker (&$f_data,$f_strict_standard = true)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserXMLReader/#echo(__FILEPATH__)# -XmlParser->xml2arrayXMLReaderArrayWalker (+f_data,+f_strict_standard)- (#echo(__LINE__)#)"; }
		$f_return = false;

		if (is_array ($f_data))
		{
			if ((strlen ($f_data['value']))||(!empty ($f_data['attributes']))||(!empty ($f_data['children'])))
			{
				if ((!$f_strict_standard)&&(isset ($f_data['attributes']['value']))&&(!strlen ($f_data['value'])))
				{
					$f_data['value'] = $f_data['attributes']['value'];
					unset ($f_data['attributes']['value']);
				}

				$this->parser->nodeAdd ($f_data['node_path'],$f_data['value'],$f_data['attributes']);
			}

			if (!empty ($f_data['children']))
			{
				foreach ($f_data['children'] as $f_child_array) { $this->xml2arrayXMLReaderArrayWalker ($f_child_array,$f_strict_standard); }
			}

			$f_return = true;
		}

		return $f_return;
	}

/**
	* Imports a pre-parsed XML array into the given parser instance.
	*
	* @param  array &$f_data Result array of a "xml2arrayXMLReaderWalker ()"
	* @param  boolean $f_strict_standard Be standard conform
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function xml2arrayXMLReaderIsValid (&$f_xmlreader)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserXMLReader/#echo(__FILEPATH__)# -XmlParser->xml2arrayXMLReaderIsValid (+f_xmlreader)- (#echo(__LINE__)#)"; }
		$f_return = is_object ($f_xmlreader);

		if (!isset ($this->PHP_is_valid)) { $this->PHP_is_valid = method_exists ($f_xmlreader,"is_valid"); }
		if (($f_return)&&($this->PHP_is_valid)) { $f_return = $f_xmlreader->is_valid (); }

		return $f_return;
	}

/**
	* Converts XML data into a merged array ... using the
	* "simplexml_load_string ()" result.
	*
	* @param  object &$f_xmlreader SimpleXMLElement object
	* @return array Merged XML tree
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2arrayXMLReaderMerged (&$f_xmlreader)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserXMLReader/#echo(__FILEPATH__)# -XmlParser->xml2arrayXMLReaderMerged (+f_xmlreader)- (#echo(__LINE__)#)"; }
		$f_return = array ();

		if ($this->xml2arrayXMLReaderIsValid ($f_xmlreader))
		{
			$f_node_change_check = false;
			$f_continue_check = true;
			$f_depth = 0;
			$f_node_path = "";
			$f_node_path_array = array ();
			$f_nodes_array = array ();
			$f_read_check = true;
			$f_timeout_time = ($this->time + $this->timeout_count);

			do
			{
				switch ($f_xmlreader->nodeType)
				{
				case $this->node_types['cdata']:
				{
					if (isset ($f_nodes_array[$f_node_path]['value'])) { $f_nodes_array[$f_node_path]['value'] .= (((isset ($f_nodes_array[$f_node_path]['attributes']['xml:space']))&&($f_nodes_array[$f_node_path]['attributes']['xml:space'] == "preserve")) ? $f_xmlreader->value : trim ($f_xmlreader->value)); }
					break 1;
				}
				case $this->node_types['element']:
				{
					$f_attributes_array = array ();
					$f_node_name = strtolower ($f_xmlreader->name);
					if (strpos ($f_node_name,"digitstart__") === 0) { $f_node_name = substr ($f_node_name,12); }

					if ($f_xmlreader->attributeCount > 0)
					{
						if ($f_xmlreader->moveToFirstAttribute ())
						{
							do
							{
								$f_attribute_name = strtolower ($f_xmlreader->name);

								if (strpos ($f_attribute_name,"xmlns:") === 0) { $f_attributes_array["xmlns:".(substr ($f_xmlreader->name,6))] = $f_xmlreader->value; }
								elseif ($f_attribute_name == "xml:space") { $f_attributes_array['xml:space'] = strtolower ($f_xmlreader->value); }
								else { $f_attributes_array[$f_attribute_name] = $f_xmlreader->value; }
							}
							while (($f_xmlreader->moveToNextAttribute ())&&($f_timeout_time > (time ())));

							$f_xmlreader->moveToElement ();
						}
					}

					$f_depth = $f_xmlreader->depth;
					$f_node_change_check = $f_xmlreader->isEmptyElement;
					$f_node_path_array = array_slice ($f_node_path_array,0,$f_depth);
					$f_node_path_array[] = $f_node_name;

					$f_node_path = implode ("_",$f_node_path_array);
					$f_nodes_array[$f_node_path] = array ("tag" => $f_node_name,"level" => ($f_depth + 1),"value" => "","attributes" => $f_attributes_array);

					$f_continue_check = $f_xmlreader->read ();
					$f_read_check = false;

					break 1;
				}
				case $this->node_types['element_end']:
				{
					$f_continue_check = $f_xmlreader->read ();
					$f_node_change_check = true;
					$f_read_check = false;
					break 1;
				}
				case $this->node_types['text']:
				{
					if (isset ($f_nodes_array[$f_node_path]['value'])) { $f_nodes_array[$f_node_path]['value'] .= (((isset ($f_nodes_array[$f_node_path]['attributes']['xml:space']))&&($f_nodes_array[$f_node_path]['attributes']['xml:space'] == "preserve")) ? $f_xmlreader->value : trim ($f_xmlreader->value)); }
					break 1;
				}
				}

				if ($f_node_change_check)
				{
					$f_node_change_check = false;

					if (!empty ($f_nodes_array[$f_node_path]))
					{
						if ((isset ($f_nodes_array[$f_node_path]['attributes']['value']))&&(!strlen ($f_nodes_array[$f_node_path]['value'])))
						{
							$f_nodes_array[$f_node_path]['value'] = $f_nodes_array[$f_node_path]['attributes']['value'];
							unset ($f_nodes_array[$f_node_path]['attributes']['value']);
						}

						if (empty ($f_nodes_array[$f_node_path]['attributes'])) { unset ($f_nodes_array[$f_node_path]['attributes']); }

						if (isset ($f_return[$f_node_path]))
						{
							if (isset ($f_return[$f_node_path]['tag']))
							{
								$f_node_packed_array = $f_return[$f_node_path];
								$f_return[$f_node_path] = array ($f_node_packed_array);
								$f_node_packed_array = NULL;
							}

							$f_return[$f_node_path][] = $f_nodes_array[$f_node_path];
						}
						else { $f_return[$f_node_path] = $f_nodes_array[$f_node_path]; }

						unset ($f_nodes_array[$f_node_path]);
					}

					$f_depth = $f_xmlreader->depth;
					array_pop ($f_node_path_array);
					$f_node_path = implode ("_",$f_node_path_array);
					$f_read_check = false;
				}
				elseif ($f_xmlreader->depth < $f_depth)
				{
					if (isset ($f_nodes_array[$f_node_path])) { unset ($f_nodes_array[$f_node_path]); }

					array_pop ($f_node_path_array);
					$f_node_path = implode ("_",$f_node_path_array);
					$f_depth = $f_xmlreader->depth;
				}

				if ($f_read_check)
				{
					if ($f_continue_check) { $f_continue_check = $f_xmlreader->read (); }
				}
				else { $f_read_check = true; }
			}
			while (($f_continue_check)&&($f_timeout_time > (time ())));

			$f_xmlreader->close ();
		}

		return $f_return;
	}

/**
	* Converts XML data into a multi-dimensional array using the recursive
	* algorithm.
	*
	* @param  object &$f_xmlreader SimpleXMLElement object
	* @param  boolean $f_strict_standard Be standard conform
	* @param  string $f_node_path Old node path (for recursive use only)
	* @param  integer $f_xml_level Current XML depth
	* @return mixed XML node array on success; false on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function xml2arrayXMLReaderWalker (&$f_xmlreader,$f_strict_standard = true,$f_node_path = "",$f_xml_level = 0)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserXMLReader/#echo(__FILEPATH__)# -XmlParser->xml2arrayXMLReaderWalker (+f_xmlreader,+f_strict_standard,$f_node_path,$f_xml_level)- (#echo(__LINE__)#)"; }
		$f_return = false;

		if (is_object ($f_xmlreader))
		{
			$f_attributes_array = array ();
			$f_continue_check = false;
			$f_node_content = "";
			$f_nodes_array = array ();
			$f_preserve_check = false;
			$f_read_check = true;
			$f_timeout_time = ($this->time + $this->timeout_count);

			while ((!$f_continue_check)&&($f_read_check)&&($f_timeout_time > (time ())))
			{
				if ($f_xmlreader->nodeType == $this->node_types['element'])
				{
					if ($f_strict_standard) { $f_node_name = $f_xmlreader->name; }
					else
					{
						$f_node_name = strtolower ($f_xmlreader->name);
						if (strpos ($f_node_name,"digitstart__") === 0) { $f_node_name = substr ($f_node_name,12); }
					}

					if ($f_xmlreader->attributeCount > 0)
					{
						if ($f_xmlreader->moveToFirstAttribute ())
						{
							do
							{
								$f_attribute_name = strtolower ($f_xmlreader->name);

								if (strpos ($f_attribute_name,"xmlns:") === 0) { $f_attributes_array["xmlns:".(substr ($f_xmlreader->name,6))] = $f_xmlreader->value; }
								elseif ($f_attribute_name == "xml:space")
								{
									$f_attributes_array['xml:space'] = strtolower ($f_xmlreader->value);
									if ($f_attributes_array['xml:space'] == "preserve") { $f_preserve_check = true; }
								}
								elseif (!$f_strict_standard) { $f_attributes_array[$f_attribute_name] = $f_xmlreader->value; }
								else { $f_attributes_array[$f_xmlreader->name] = $f_xmlreader->value; }
							}
							while (($f_xmlreader->moveToNextAttribute ())&&($f_timeout_time > (time ())));

							$f_xmlreader->moveToElement ();
						}
					}

					$f_continue_check = true;
				}

				$f_read_check = $f_xmlreader->read ();
			}

			if ($f_continue_check)
			{
				if (strlen ($f_node_path)) { $f_node_path = $f_node_path." ".$f_node_name; }
				else { $f_node_path = $f_node_name; }
			}

			while (($f_continue_check)&&($f_timeout_time > (time ())))
			{
				if ($f_xml_level < $f_xmlreader->depth)
				{
					switch ($f_xmlreader->nodeType)
					{
					case $this->node_types['cdata']:
					{
						$f_node_content .= (($f_preserve_check) ? $f_xmlreader->value : trim ($f_xmlreader->value));
						break 1;
					}
					case $this->node_types['element']:
					{
						$f_nodes_array[] = $this->xml2arrayXMLReaderWalker ($f_xmlreader,$f_strict_standard,$f_node_path,$f_xmlreader->depth);
						$f_read_check = false;
						break 1;
					}
					case $this->node_types['element_end']:
					{
						$f_read_check = false;
						if (!$f_xmlreader->read ()) { $f_continue_check = false; }
						break 1;
					}
					case $this->node_types['text']:
					{
						$f_node_content .= (($f_preserve_check) ? $f_xmlreader->value : trim ($f_xmlreader->value));
						break 1;
					}
					default:
					{
						if (($f_preserve_check)&&(($f_xmlreader->nodeType == $this->node_types['whitespace'])||($f_xmlreader->nodeType == $this->node_types['whitespace_significant']))) { $f_node_content .= $f_xmlreader->value; }
					}
					}

					if ($f_read_check)
					{
						if ($f_continue_check) { $f_continue_check = $f_xmlreader->read (); }
						elseif (!$f_xmlreader->read ()) { $f_continue_check = false; }
					}
					else { $f_read_check = true; }
				}
				else { $f_continue_check = false; }
			}

			$f_return = array ("node_path" => $f_node_path,"value" => $f_node_content,"attributes" => $f_attributes_array,"children" => $f_nodes_array);
		}

		return $f_return;
	}
}

/* -------------------------------------------------------------------------
Mark this class as the most up-to-date one
------------------------------------------------------------------------- */

define ("CLASS_directXmlParserXMLReader",true);
}

//j// EOF
?>