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

if (!defined ("CLASS_directXmlParserExpat"))
{
/**
* This implementation supports expat for XML parsing.
*
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    ext_core
* @subpackage xml
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;w3c
*             W3C (R) Software License
*/
class directXmlParserExpat
{
/**
	* @var boolean $data_merged_mode True if the parser is set to merged
	*      mode
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data_merged_mode;
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
	* @var string $node_path Current node path of the parser
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $node_path;
/**
	* @var array $node_path_array Current path as an array of node tags
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $node_path_array;
/**
	* @var integer $node_path_level Current depth
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $node_path_depth;
/**
	* @var direct_xml_reader $parser Container for the XML document
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $parser;
/**
	* @var boolean $parser_active True if not the last element has been
	*      reached
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $parser_active;
/**
	* @var array $parser_cache Parser data cache
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $parser_cache;
/**
	* @var integer $parser_cache_counter Cache entry counter
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $parser_cache_counter;
/**
	* @var array $parser_cache_link Links to the latest entry added
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $parser_cache_link;
/**
	* @var boolean $parser_strict_standard True to be standard conform
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $parser_strict_standard;

/* -------------------------------------------------------------------------
Construct the class using old and new behavior
------------------------------------------------------------------------- */

/**
	* Constructor (PHP5+) __construct (directXmlParserExpat)
	*
	* @param direct_xml_reader &$f_parser Container for the XML document
	* @param boolean $f_debug Debug flag
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __construct (&$f_parser,$f_debug = false)
	{
		$this->debugging = $f_debug;
		if ($this->debugging) { $this->debug = array ("directXmlParserExpat/#echo(__FILEPATH__)# -XmlParser->__construct (directXmlParserExpat)- (#echo(__LINE__)#)"); }

/* -------------------------------------------------------------------------
Connect to the PHP container for the XML document 
------------------------------------------------------------------------- */

		$this->data_merged_mode = false;
		$this->node_path_array = array ();
		$this->parser = $f_parser;
		$this->parser_active = false;
		$this->parser_strict_standard = true;
	}
/*#ifdef(PHP4):
/**
	* Constructor (PHP4) directXmlParserExpat
	*
	* @param direct_xml_reader &$f_parser Container for the XML document
	* @param boolean $f_debug Debug flag
	* @since v0.1.00
*\/
	function directXmlParserExpat (&$f_parser,$f_debug = false) { $this->__construct ($f_parser,$f_debug); }
:#\n*/
/**
	* Destructor (PHP5+) __destruct (directXmlParserExpat)
	*
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __destruct () { $this->parser = NULL; }

/**
	* Define the parser mode ("tree" or "merged").
	*
	* @param  string $f_mode Mode to select
	* @return boolean True if parser is set to merged mode
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function defineMode ($f_mode = "")
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserExpat/#echo(__FILEPATH__)# -XmlParser->defineMode ($f_mode)- (#echo(__LINE__)#)"; }

		if ((!$this->parser_active)&&(is_string ($f_mode))) { $this->data_merged_mode = (($f_mode == "merged") ? true : false); }
		return $this->data_merged_mode;
	}

/**
	* Changes the parser mode regarding being strict standard conform.
	*
	* @param  boolean $f_strict_standard Be standard conform
	* @return boolean Accepted state
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function defineStrictStandard ($f_strict_standard = true)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserExpat/#echo(__FILEPATH__)# -XmlParser->defineStrictStandard (+f_strict_standard)- (#echo(__LINE__)#)"; }

		if (!$this->parser_active)
		{
			if (((is_bool ($f_strict_standard))||(is_string ($f_strict_standard)))&&($f_strict_standard)) { $this->parser_strict_standard = true; }
			elseif (($f_strict_standard === NULL)&&(!$this->parser_strict_standard)) { $this->parser_strict_standard = true; }
			else { $this->parser_strict_standard = false; }
		}

		return $this->parser_strict_standard;
	}

/**
	* php.net: Character data handler is called for every piece of a text in the
	* XML document. It can be called multiple times inside each fragment (e.g.
	* for non-ASCII strings).
	*
	* @param object $f_parser XML parser calling this handler
	* @param string $f_data Character data
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function expatCData ($f_parser,$f_data)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserExpat/#echo(__FILEPATH__)# -XmlParser->expatCData (+f_parser,+f_data)- (#echo(__LINE__)#)"; }

		if ($this->parser_active)
		{
			if (isset ($this->parser_cache[$this->parser_cache_link[$this->node_path]]['value'])) { $this->parser_cache[$this->parser_cache_link[$this->node_path]]['value'] .= $f_data; }
			else { $this->parser_cache[$this->parser_cache_link[$this->node_path]]['value'] = $f_data; }
		}
	}

/**
	* Method to handle "end element" callbacks.
	*
	* @param object $f_parser XML parser calling this handler
	* @param string $f_tag XML tag
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function expatElementEnd ($f_parser,$f_tag)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserExpat/#echo(__FILEPATH__)# -XmlParser->expatElementEnd (+f_parser,$f_tag)- (#echo(__LINE__)#)"; }

		if ($this->parser_active)
		{
			$f_node_path = $this->parser_cache_link[$this->node_path];

			unset ($this->parser_cache_link[$this->node_path]);
			array_pop ($this->node_path_array);
			$this->node_path_depth--;
			$this->node_path = implode (" ",$this->node_path_array);

			if (isset ($this->parser_cache[$f_node_path]['value']))
			{
				if (isset ($this->parser_cache[$f_node_path]['attributes']['xml:space']))
				{
					if ($this->parser_cache[$f_node_path]['attributes']['xml:space'] != "preserve") { $this->parser_cache[$f_node_path]['value'] = trim ($this->parser_cache[$f_node_path]['value']); }
				}
				else { $this->parser_cache[$f_node_path]['value'] = trim ($this->parser_cache[$f_node_path]['value']); }
			}
			else { $this->parser_cache[$f_node_path]['value'] = ""; }

			if ((!$this->parser_strict_standard)&&(isset ($this->parser_cache[$f_node_path]['attributes']['value']))&&(!strlen ($this->parser_cache[$f_node_path]['value'])))
			{
				$this->parser_cache[$f_node_path]['value'] = $this->parser_cache[$f_node_path]['attributes']['value'];
				unset ($this->parser_cache[$f_node_path]['attributes']['value']);
			}

			if ($this->node_path_depth < 1)
			{
				$this->node_path = "";
				$this->parser_active = false;
			}
		}
	}

/**
	* php.net: Character data handler is called for every piece of a text in the
	* XML document. It can be called multiple times inside each fragment (e.g.
	* for non-ASCII strings). (Merged XML parser)
	*
	* @param object $f_parser XML parser calling this handler
	* @param string $f_data Character data
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function expatMergedCData ($f_parser,$f_data)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserExpat/#echo(__FILEPATH__)# -XmlParser->expatMergedCData (+f_parser,+f_data)- (#echo(__LINE__)#)"; }

		if ($this->parser_active)
		{
			if ($this->parser_cache_link[$this->node_path] > 0) { $this->parser_cache[$this->node_path][$this->parser_cache_link[$this->node_path]]['value'] .= $f_data; }
			else { $this->parser_cache[$this->node_path]['value'] .= $f_data; }
		}
	}

/**
	* Method to handle "end element" callbacks. (Merged XML parser)
	*
	* @param object $f_parser XML parser calling this handler
	* @param string $f_tag XML tag
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function expatMergedElementEnd ($f_parser,$f_tag)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserExpat/#echo(__FILEPATH__)# -XmlParser->expatMergedElementEnd (+f_parser,$f_tag)- (#echo(__LINE__)#)"; }
	
		if ($this->parser_active)
		{
			if ($this->parser_cache_link[$this->node_path] > 0) { $f_node_pointer =& $this->parser_cache[$this->node_path][$this->parser_cache_link[$this->node_path]]; }
			else { $f_node_pointer =& $this->parser_cache[$this->node_path]; }

			array_pop ($this->node_path_array);
			$this->node_path_depth--;
			$this->node_path = implode ("_",$this->node_path_array);

			if (isset ($f_node_pointer['attributes']['xml:space']))
			{
				if ($f_node_pointer['attributes']['xml:space'] != "preserve") { $f_node_pointer['value'] = trim ($f_node_pointer['value']); }
			}
			else { $f_node_pointer['value'] = trim ($f_node_pointer['value']); }

			if ((isset ($f_node_pointer['attributes']['value']))&&(!strlen ($f_node_pointer['value'])))
			{
				$f_node_pointer['value'] = $f_node_pointer['attributes']['value'];
				unset ($f_node_pointer['attributes']['value']);
			}

			if ($this->node_path_depth < 1)
			{
				$this->node_path = "";
				$this->parser_active = false;
			}
		}
	}

/**
	* Method to handle "start element" callbacks. (Merged XML parser)
	*
	* @param object $f_parser XML parser calling this handler
	* @param string $f_tag XML tag
	* @param array $f_attributes Node attributes
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function expatMergedElementStart ($f_parser,$f_tag,$f_attributes)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserExpat/#echo(__FILEPATH__)# -XmlParser->expatMergedElementStart (+f_parser,$f_tag,+f_attributes)- (#echo(__LINE__)#)"; }

		if (!$this->parser_active)
		{
			$this->node_path = "";
			$this->node_path_depth = 0;
			$this->parser_active = true;
			$this->parser_cache_link = array ();
		}

		$f_tag = strtolower ($f_tag);
		if (strpos ($f_tag,"digitstart__") === 0) { $f_tag = substr ($f_tag,12); }

		if ($this->node_path) { $this->node_path .= "_"; }
		$this->node_path .= $f_tag;
		$this->node_path_array[] = $f_tag;
		$this->node_path_depth++;

		foreach ($f_attributes as $f_key => $f_value)
		{
			$f_key_lowercase = strtolower ($f_key);

			if (strpos ($f_key_lowercase,"xmlns:") === 0)
			{
				$f_ns_name = substr ($f_key,6);
				$f_attributes["xmlns:".$f_ns_name] = $f_value;
				if ($f_key != "xmlns:".$f_ns_name) { unset ($f_attributes[$f_key]); }
			}
			elseif ($f_key_lowercase == "xml:space")
			{
				$f_attributes[$f_key_lowercase] = strtolower ($f_value);
				if ($f_key != $f_key_lowercase) { unset ($f_attributes[$f_key]); }
			}
			elseif ($f_key != $f_key_lowercase)
			{
				unset ($f_attributes[$f_key]);
				$f_attributes[$f_key_lowercase] = $f_value;
			}
		}

		$f_node_array = array ("tag" => $f_tag,"level" => $this->node_path_depth,"value" => "","attributes" => $f_attributes);

		if (isset ($this->parser_cache[$this->node_path]))
		{
			if (isset ($this->parser_cache[$this->node_path]['tag'])) { $this->parser_cache[$this->node_path] = array ($this->parser_cache[$this->node_path],$f_node_array); }
			else { $this->parser_cache[$this->node_path][] = $f_node_array; }

			$this->parser_cache_link[$this->node_path]++;
		}
		else
		{
			$this->parser_cache[$this->node_path] = $f_node_array;
			$this->parser_cache_link[$this->node_path] = 0;
		}
	}

/**
	* Method to handle "start element" callbacks.
	*
	* @param object $f_parser XML parser calling this handler
	* @param string $f_tag XML tag
	* @param array $f_attributes Node attributes
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function expatElementStart ($f_parser,$f_tag,$f_attributes)
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserExpat/#echo(__FILEPATH__)# -XmlParser->expatElementStart (+f_parser,$f_tag,+f_attributes)- (#echo(__LINE__)#)"; }

		if (!$this->parser_active)
		{
			$this->node_path = "";
			$this->node_path_depth = 0;
			$this->parser_active = true;
			$this->parser_cache_counter = 0;
			$this->parser_cache_link = array ();
		}

		if (!$this->parser_strict_standard)
		{
			$f_tag = strtolower ($f_tag);
			if (strpos ($f_tag,"digitstart__") === 0) { $f_tag = substr ($f_tag,12); }
		}

		if ($this->node_path) { $this->node_path .= " "; }
		$this->node_path .= $f_tag;
		$this->node_path_array[] = $f_tag;
		$this->node_path_depth++;

		foreach ($f_attributes as $f_key => $f_value)
		{
			$f_key_lowercase = strtolower ($f_key);

			if (strpos ($f_key_lowercase,"xmlns:") === 0)
			{
				$f_ns_name = substr ($f_key,6);
				$f_attributes["xmlns:".$f_ns_name] = $f_value;
				if ($f_key != "xmlns:".$f_ns_name) { unset ($f_attributes[$f_key]); }
			}
			elseif ($f_key_lowercase == "xml:space")
			{
				$f_attributes[$f_key_lowercase] = strtolower ($f_value);
				if ($f_key != $f_key_lowercase) { unset ($f_attributes[$f_key]); }
			}
			elseif ((!$this->parser_strict_standard)&&($f_key != $f_key_lowercase))
			{
				unset ($f_attributes[$f_key]);
				$f_attributes[$f_key_lowercase] = $f_value;
			}
		}

		$this->parser_cache[] = array ("node_path" => $this->node_path,"attributes" => $f_attributes);
		$this->parser_cache_link[$this->node_path] = $this->parser_cache_counter;
		$this->parser_cache_counter++;
	}

/**
	* Adds the result of an expat parsing operation to the defined XML instance
	* if the parser completed its work.
	*
	* @return array Multi-dimensional XML tree
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2arrayExpat ()
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserExpat/#echo(__FILEPATH__)# -XmlParser->xml2arrayExpat ()- (#echo(__LINE__)#)"; }
		$f_return = array ();

		if ((!$this->parser_active)&&(is_array ($this->parser_cache))&&(!empty ($this->parser_cache)))
		{
			$this->parser->set (array ());

			foreach ($this->parser_cache as $f_node_array) { $this->parser->nodeAdd ($f_node_array['node_path'],$f_node_array['value'],$f_node_array['attributes']); }

			$this->parser_cache = array ();
			$f_return = $this->parser->get ();
		}

		return $f_return;
	}

/**
	* Returns the merged result of an expat parsing operation if the parser
	* completed its work.
	*
	* @return array Merged XML tree
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2arrayExpatMerged ()
	{
		if ($this->debugging) { $this->debug[] = "directXmlParserExpat/#echo(__FILEPATH__)# -XmlParser->xml2arrayExpatMerged ()- (#echo(__LINE__)#)"; }
		$f_return = array ();

		if ((!$this->parser_active)&&(is_array ($this->parser_cache))&&(!empty ($this->parser_cache)))
		{
			$f_return = $this->parser_cache;
			$this->parser_cache = array ();
		}

		return $f_return;
	}
}

/* -------------------------------------------------------------------------
Mark this class as the most up-to-date one
------------------------------------------------------------------------- */

define ("CLASS_directXmlParserExpat",true);
}

//j// EOF
?>