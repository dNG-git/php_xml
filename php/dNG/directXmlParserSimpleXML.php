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
/*#ifdef(PHP5n) */

namespace dNG;
/* #\n*/

/* -------------------------------------------------------------------------
All comments will be removed in the "production" packages (they will be in
all development packets)
------------------------------------------------------------------------- */

//j// Functions and classes

if (!defined ("CLASS_directXmlParserSimpleXML"))
{
/**
* This implementation supports SimpleXML for XML parsing.
*
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    ext_core
* @subpackage xml
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;w3c
*             W3C (R) Software License
*/
class directXmlParserSimpleXML
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

/* -------------------------------------------------------------------------
Construct the class using old and new behavior
------------------------------------------------------------------------- */

/**
	* Constructor (PHP5+) __construct (directXmlParserSimpleXML)
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
		if ($this->debugging) { $this->debug = array ("directXmlParserSimpleXML/#echo(__FILEPATH__)# -XmlParser->__construct (directXmlParserSimpleXML)- (#echo(__LINE__)#)"); }

/* -------------------------------------------------------------------------
Connect to the PHP container for the XML document 
------------------------------------------------------------------------- */

		$this->parser = $f_parser;
		$this->time = (($f_time < 0) ? time () : $f_time);
		$this->timeout_count = $f_timeout_count;
	}
/*#ifdef(PHP4):
/**
	* Constructor (PHP4) directXmlParserSimpleXML
	*
	* @param direct_xml_reader &$f_parser Container for the XML document
	* @param integer $f_time Current UNIX timestamp
	* @param integer $f_timeout_count Retries before timing out
	* @param boolean $f_debug Debug flag
	* @since v0.1.00
*\/
	function directXmlParserSimpleXML (&$f_parser,$f_time = -1,$f_timeout_count = 5,$f_debug = false) { $this->__construct ($f_parser,$f_time,$f_timeout_count,$f_debug); }
:#\n*/
/**
	* Destructor (PHP5+) __destruct (directXmlParserSimpleXML)
	*
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __destruct () { $this->parser = NULL; }

/**
	* Converts XML data into a multi-dimensional array ... using the
	* "simplexml_load_string ()" result.
	*
	* @param  object &$f_xmlelement SimpleXMLElement object
	* @param  boolean $f_strict_standard Be standard conform
	* @return array Multi-dimensional XML tree
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2arraySimpleXML (&$f_xmlelement,$f_strict_standard = true)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserSimpleXML/#echo(__FILEPATH__)# -XmlParser->xml2arraySimpleXML (+f_xmlreader,+f_strict_standard)- (#echo(__LINE__)#)"; }
		$f_return = array ();

		if (is_object ($f_xmlelement))
		{
			$this->parser->set (array ());
			$f_xmlelement_array = $this->xml2arraySimpleXMLWalker ($f_xmlelement,$f_strict_standard);

			if ($f_xmlelement_array) { $f_continue_check = $this->xml2arraySimpleXMLArrayWalker ($f_xmlelement_array,$f_strict_standard); }
			if ($f_continue_check) { $f_return = $this->parser->get (); }
		}

		return $f_return;
	}

/**
	* Imports a pre-parsed XML array into the given parser instance.
	*
	* @param  array &$f_data Result array of a "xml2arraySimpleXMLWalker ()"
	* @param  boolean $f_strict_standard Be standard conform
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function xml2arraySimpleXMLArrayWalker (&$f_data,$f_strict_standard = true)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserSimpleXML/#echo(__FILEPATH__)# -XmlParser->xml2arraySimpleXMLArrayWalker (+f_data,+f_strict_standard)- (#echo(__LINE__)#)"; }
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
				foreach ($f_data['children'] as $f_child_array) { $this->xml2arraySimpleXMLArrayWalker ($f_child_array,$f_strict_standard); }
			}

			$f_return = true;
		}

		return $f_return;
	}

/**
	* Converts XML data into a merged array ... using the
	* "simplexml_load_string ()" result.
	*
	* @param  object &$f_xmlelement SimpleXMLElement object
	* @return array Merged XML tree
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2arraySimpleXMLMerged (&$f_xmlelement)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserSimpleXML/#echo(__FILEPATH__)# -XmlParser->xml2arraySimpleXMLMerged (+f_xmlreader)- (#echo(__LINE__)#)"; }
		$f_return = array ();

		if (is_object ($f_xmlelement))
		{
			$f_xmlelement_array = $this->xml2arraySimpleXMLWalker ($f_xmlelement,false);
			if ($f_xmlelement_array) { $f_return = $this->xml2arraySimpleXMLMergedArrayWalker ($f_xmlelement_array); }
		}

		return $f_return;
	}

/**
	* Converts a pre-parsed XML array into a merged array.
	*
	* @param  array &$f_data Result array of a "xml2arraySimpleXMLWalker ()"
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2arraySimpleXMLMergedArrayWalker (&$f_data,$f_return = NULL)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserSimpleXML/#echo(__FILEPATH__)# -XmlParser->xml2arraySimpleXMLMergedArrayWalker (+f_data,+f_return)- (#echo(__LINE__)#)"; }
		if (!isset ($f_return)) { $f_return = array (); }

		if (is_array ($f_data))
		{
			if ((strlen ($f_data['value']))||(!empty ($f_data['attributes'])))
			{
				$f_node_array = array ("tag" => substr ($f_data['node_path'],(strrpos ($f_data['node_path']," "))),"value" => $f_data['value'],"attributes" => $f_data['attributes']);

				if ((isset ($f_data['attributes']['value']))&&(!strlen ($f_data['value'])))
				{
					$f_node_array['value'] = $f_data['attributes']['value'];
					unset ($f_node_array['attributes']['value']);
				}

				$f_node_path = str_replace (" ","_",$f_data['node_path']);

				if (isset ($f_return[$f_node_path]))
				{
					if (isset ($f_return[$f_node_path]['tag']))
					{
						$f_node_packed_array = $f_return[$f_node_path];
						$f_return[$f_node_path] = array ($f_node_packed_array);
						$f_node_packed_array = NULL;
					}

					$f_return[$f_node_path][] = $f_node_array;
				}
				else { $f_return[$f_node_path] = $f_node_array; }
			}

			if (isset ($f_data['children']))
			{
				foreach ($f_data['children'] as $f_child_array) { $f_return = $this->xml2arraySimpleXMLMergedArrayWalker ($f_child_array,$f_return); }
			}
		}

		return $f_return;
	}

/**
	* Converts XML data into a multi-dimensional array using the recursive
	* algorithm.
	*
	* @param  object &$f_xmlelement SimpleXMLElement object
	* @param  boolean $f_strict_standard Be standard conform
	* @param  string $f_node_path Old node path (for recursive use only)
	* @param  integer $f_xml_level Current XML depth
	* @return mixed XML node array on success; false on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function xml2arraySimpleXMLWalker (&$f_xmlelement,$f_strict_standard = true,$f_node_path = "",$f_xml_level = 0)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserSimpleXML/#echo(__FILEPATH__)# -XmlParser->xml2arraySimpleXMLWalker (+f_xmlreader,+f_strict_standard,$f_node_path,$f_xml_level)- (#echo(__LINE__)#)"; }
		$f_return = false;

		if (is_object ($f_xmlelement))
		{
			$f_attributes_array = array ();
			$f_continue_check = false;
			$f_preserve_check = false;
			$f_xmlelement_subnode = NULL;

			if ($f_strict_standard) { $f_node_name = $f_xmlelement->getName (); }
			else
			{
				$f_node_name = strtolower ($f_xmlelement->getName ());
				if (strpos ($f_node_name,"digitstart__") === 0) { $f_node_name = substr ($f_node_name,12); }
			}

			if (strlen ($f_node_path)) { $f_node_path = $f_node_path." ".$f_node_name; }
			else { $f_node_path = $f_node_name; }

			$f_xmlelement_attributes = $f_xmlelement->attributes ();

			if (($f_xmlelement_attributes == NULL)||(empty ($f_xmlelement_attributes))) { $f_attributes_array = array (); }
			else
			{
				$f_attributes_array = array ();

				foreach ($f_xmlelement_attributes as $f_attribute => $f_value)
				{
					$f_attribute_name = strtolower ($f_attribute);

					if (strpos ($f_attribute_name,"xmlns:") === 0) { $f_attributes_array["xmlns:".(substr ($f_attribute,6))] = (string)$f_value; }
					elseif ($f_attribute_name == "xml:space")
					{
						$f_attributes_array['xml:space'] = strtolower ((string)$f_value);
						if ($f_attributes_array['xml:space'] == "preserve") { $f_preserve_check = true; }
					}
					elseif (!$f_strict_standard) { $f_attributes_array[$f_attribute_name] = (string)$f_value; }
					else { $f_attributes_array[$f_attribute] = (string)$f_value; }
				}
			}

			$f_nodes_array = array ();
			$f_timeout_time = ($this->time + $this->timeout_count);
			$f_xmlelement_subnodes = $f_xmlelement->children ();

			foreach ($f_xmlelement_subnodes as $f_xmlelement_subnode)
			{
				if ($f_timeout_time > (time ())) { $f_nodes_array[] = $this->xml2arraySimpleXMLWalker ($f_xmlelement_subnode,$f_strict_standard,$f_node_path,($f_xml_level + 1)); }
			}

			$f_return = array ("node_path" => $f_node_path,"value" => ($f_preserve_check ? (string)$f_xmlelement : trim ((string)$f_xmlelement)),"attributes" => $f_attributes_array,"children" => $f_nodes_array);
		}

		return $f_return;
	}
}

/* -------------------------------------------------------------------------
Mark this class as the most up-to-date one
------------------------------------------------------------------------- */

define ("CLASS_directXmlParserSimpleXML",true);
}

//j// EOF
?>