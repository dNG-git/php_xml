<?php
//j// BOF

/*n// NOTE
----------------------------------------------------------------------------
Extended Core: XML
Multiple XML parser abstraction layer
----------------------------------------------------------------------------
(C) direct Netware Group - All rights reserved
http://www.direct-netware.de/redirect.php?ext_core_xml

This work is distributed under the W3C (R) Software License, but without any
warranty; without even the implied warranty of merchantability or fitness
for a particular purpose.
----------------------------------------------------------------------------
http://www.direct-netware.de/redirect.php?licenses;w3c
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
* @license    http://www.direct-netware.de/redirect.php?licenses;w3c
*             W3C (R) Software License
*/

/* -------------------------------------------------------------------------
All comments will be removed in the "production" packages (they will be in
all development packets)
------------------------------------------------------------------------- */

//j// Functions and classes

/* -------------------------------------------------------------------------
Testing for required classes
------------------------------------------------------------------------- */

if (!defined ("CLASS_direct_xml_parser_XMLReader"))
{
//c// direct_xml_parser_XMLReader
/**
* This implementation supports XMLReader for XML parsing.
*
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    ext_core
* @subpackage xml
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;w3c
*             W3C (R) Software License
*/
class direct_xml_parser_XMLReader
{
/**
	* @var direct_xml_reader $parser Container for the XML document
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $parser;
/**
	* @var array $node_types Node types that this parser knows
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $node_types;
/**
	* @var integer $time Current UNIX timestamp
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $time;
/**
	* @var integer $timeout_count Retries before timing out
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $timeout_count;

/* -------------------------------------------------------------------------
Construct the class using old and new behavior
------------------------------------------------------------------------- */

	//f// direct_xml_parser_XMLReader->__construct (&$f_parser) and direct_xml_parser_XMLReader->direct_xml_parser_XMLReader (&$f_parser)
/**
	* Constructor (PHP5+) __construct (direct_xml_parser_XMLReader)
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
		if ($this->debugging) { $this->debug = array ("xml/#echo(__FILEPATH__)# -xml_parser->__construct (direct_xml_parser_XMLReader)- (#echo(__LINE__)#)"); }

/* -------------------------------------------------------------------------
Connect to the PHP container for the XML document 
------------------------------------------------------------------------- */

		$this->parser = $f_parser;

		if ($f_time < 0) { $this->time = time (); }
		else { $this->time = $f_time; }

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
	* Constructor (PHP4) direct_xml_parser_XMLReader (direct_xml_parser_XMLReader)
	*
	* @param direct_xml_reader &$f_parser Container for the XML document
	* @param integer $f_time Current UNIX timestamp
	* @param integer $f_timeout_count Retries before timing out
	* @param boolean $f_debug Debug flag
	* @since v0.1.00
*\/
	function direct_xml_parser_XMLReader (&$f_parser,$f_time = -1,$f_timeout_count = 5,$f_debug = false) { $this->__construct ($f_parser,$f_time,$f_timeout_count,$f_debug); }
:#\n*/
	//f// direct_xml_parser_XMLReader->__destruct ()
/**
	* Destructor (PHP5+) __destruct (direct_xml_parser_XMLReader)
	*
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __destruct () { $this->parser = NULL; }

	//f// direct_xml_parser_XMLReader->xml2array_XMLReader (&$f_xmlreader,$f_strict_standard = true)
/**
	* Converts XML data into a multi-dimensional array ... using the
	* "simplexml_load_string ()" result.
	*
	* @param  object &$f_xmlreader SimpleXMLElement object
	* @param  boolean $f_strict_standard Be standard conform
	* @uses   direct_xml_reader::node_add()
	* @return array Multi-dimensional XML tree
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2array_XMLReader (&$f_xmlreader,$f_strict_standard = true)
	{
		if ($this->debugging) { $this->debug[] = "xml/#echo(__FILEPATH__)# -xml_parser->xml2array_XMLReader (+f_XMLReader,+f_strict_standard)- (#echo(__LINE__)#)"; }
		$f_return = array ();

		if (is_object ($f_xmlreader))
		{
			$f_continue_check = true;
			$f_timeout_time = ($this->time + $this->timeout_count);
			$this->parser->set (array ());

			do { $f_continue_check = $f_xmlreader->read (); }
			while (($f_continue_check)&&($f_xmlreader->nodeType != $this->node_types['element'])&&($f_timeout_time > (time ())));

			$f_XMLReader_array = $this->xml2array_XMLReader_walker ($f_xmlreader,$f_strict_standard);
			$f_xmlreader->close ();

			if ($f_XMLReader_array) { $f_continue_check = $this->xml2array_XMLReader_array_walker ($f_XMLReader_array,$f_strict_standard); }
			if ($f_continue_check) { $f_return = $this->parser->get (); }
		}

		return $f_return;
	}

	//f// direct_xml_parser_XMLReader->xml2array_XMLReader_array_walker (&$f_data,$f_strict_standard = true)
/**
	* Imports a pre-parsed XML array into the given parser instance.
	*
	* @param  array &$f_data Result array of a
	*         "xml2array_XMLReader_walker ()"
	* @param  boolean $f_strict_standard Be standard conform
	* @uses   direct_xml_reader::node_add()
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function xml2array_XMLReader_array_walker (&$f_data,$f_strict_standard = true)
	{
		if ($this->debugging) { $this->debug[] = "xml/#echo(__FILEPATH__)# -xml_parser->xml2array_XMLReader_array_walker (+f_data,+f_strict_standard)- (#echo(__LINE__)#)"; }
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

				$this->parser->node_add ($f_data['node_path'],$f_data['value'],$f_data['attributes']);
			}

			if (!empty ($f_data['children']))
			{
				foreach ($f_data['children'] as $f_child_array) { $this->xml2array_XMLReader_array_walker ($f_child_array,$f_strict_standard); }
			}

			$f_return = true;
		}

		return $f_return;
	}

	//f// direct_xml_parser_XMLReader->xml2array_XMLReader_merged (&$f_xmlreader)
/**
	* Converts XML data into a merged array ... using the
	* "simplexml_load_string ()" result.
	*
	* @param  object &$f_xmlreader SimpleXMLElement object
	* @uses   direct_xml_reader::xml2array_XMLReader_merged_walker()
	* @return array Merged XML tree
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2array_XMLReader_merged (&$f_xmlreader)
	{
		if ($this->debugging) { $this->debug[] = "xml/#echo(__FILEPATH__)# -xml_parser->xml2array_XMLReader_merged (+f_XMLReader)- (#echo(__LINE__)#)"; }
		$f_return = array ();

		if (is_object ($f_xmlreader))
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
					if (isset ($f_nodes_array[$f_node_path]['value']))
					{
						if ((isset ($f_nodes_array[$f_node_path]['attributes']['xml:space']))&&($f_nodes_array[$f_node_path]['attributes']['xml:space'] == "preserve")) { $f_nodes_array[$f_node_path]['value'] .= $f_xmlreader->value; }
						else { $f_nodes_array[$f_node_path]['value'] .= trim ($f_xmlreader->value); }
					}

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

								if ($f_attribute_name == "xml:space") { $f_attributes_array['xml:space'] = strtolower ($f_xmlreader->value); }
								else { $f_attributes_array[$f_attribute_name] = $f_xmlreader->value; }
							}
							while (($f_xmlreader->moveToNextAttribute ())&&($f_timeout_time > (time ())));

							$f_xmlreader->moveToElement ();
						}
					}

					$f_node_path_array[] = $f_node_name;
					$f_node_path = implode ("_",$f_node_path_array);
					$f_nodes_array[$f_node_path] = array ("tag" => $f_node_name,"level" => ($f_xmlreader->depth + 1),"value" => "","attributes" => $f_attributes_array);

					$f_depth = $f_xmlreader->depth;
					$f_continue_check = $f_xmlreader->read ();
					$f_read_check = false;

					if ($f_depth >= $f_xmlreader->depth) { $f_node_change_check = true; }

					break 1;
				}
				case $this->node_types['element_end']:
				{
					$f_continue_check = $f_xmlreader->read ();
					$f_read_check = false;
					$f_node_change_check = true;
					break 1;
				}
				case $this->node_types['text']:
				{
					if (isset ($f_nodes_array[$f_node_path]['value']))
					{
						if ((isset ($f_nodes_array[$f_node_path]['attributes']['xml:space']))&&($f_nodes_array[$f_node_path]['attributes']['xml:space'] == "preserve")) { $f_nodes_array[$f_node_path]['value'] .= $f_xmlreader->value; }
						else { $f_nodes_array[$f_node_path]['value'] .= trim ($f_xmlreader->value); }
					}

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

					array_pop ($f_node_path_array);
					$f_read_check = false;
					$f_node_path = implode ("_",$f_node_path_array);
					$f_depth = $f_xmlreader->depth;
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

	//f// direct_xml_parser_XMLReader->xml2array_XMLReader_walker (&$f_xmlreader,$f_strict_standard = true,$f_node_path = "",$f_xml_level = 0)
/**
	* Converts XML data into a multi-dimensional array using the recursive
	* algorithm.
	*
	* @param  object &$f_xmlreader SimpleXMLElement object
	* @param  boolean $f_strict_standard Be standard conform
	* @param  string $f_node_path Old node path (for recursive use only)
	* @param  integer $f_xml_level Current XML depth
	* @uses   direct_xml_reader::node_add()
	* @return mixed XML node array on success; false on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function xml2array_XMLReader_walker (&$f_xmlreader,$f_strict_standard = true,$f_node_path = "",$f_xml_level = 0)
	{
		if ($this->debugging) { $this->debug[] = "xml/#echo(__FILEPATH__)# -xml_parser->xml2array_XMLReader_walker (+f_XMLReader,+f_strict_standard,$f_node_path,$f_xml_level)- (#echo(__LINE__)#)"; }
		$f_return = false;

		if (is_object ($f_xmlreader))
		{
			$f_attributes_array = array ();
			$f_continue_check = true;
			$f_node_content = "";
			$f_nodes_array = array ();
			$f_preserve_check = false;
			$f_read_check = true;
			$f_timeout_time = ($this->time + $this->timeout_count);

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

							if ($f_attribute_name == "xml:space")
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

				$f_xmlreader->read ();
			}

			if (strlen ($f_node_path)) { $f_node_path = $f_node_path." ".$f_node_name; }
			else { $f_node_path = $f_node_name; }

			do
			{
				if ($f_xml_level < $f_xmlreader->depth)
				{
					switch ($f_xmlreader->nodeType)
					{
					case $this->node_types['cdata']:
					{
						if ($f_preserve_check) { $f_node_content .= $f_xmlreader->value; }
						else { $f_node_content .= trim ($f_xmlreader->value); }

						break 1;
					}
					case $this->node_types['element']:
					{
						$f_nodes_array[] = $this->xml2array_XMLReader_walker ($f_xmlreader,$f_strict_standard,$f_node_path,$f_xmlreader->depth);
						$f_read_check = false;
						break 1;
					}
					case $this->node_types['element_end']:
					{
						$f_read_check = false;
						$f_xmlreader->read ();
						break 1;
					}
					case $this->node_types['text']:
					{
						if ($f_preserve_check) { $f_node_content .= $f_xmlreader->value; }
						else { $f_node_content .= trim ($f_xmlreader->value); }

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
						else { $f_xmlreader->read (); }
					}
					else { $f_read_check = true; }
				}
				else { $f_continue_check = false; }
			}
			while (($f_continue_check)&&($f_timeout_time > (time ())));

			$f_return = array ("node_path" => $f_node_path,"value" => $f_node_content,"attributes" => $f_attributes_array,"children" => $f_nodes_array);
		}

		return $f_return;
	}
}

/* -------------------------------------------------------------------------
Mark this class as the most up-to-date one
------------------------------------------------------------------------- */

define ("CLASS_direct_xml_parser_XMLReader",true);
}

//j// EOF
?>