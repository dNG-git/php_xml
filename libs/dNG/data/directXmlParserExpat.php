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
* This implementation supports expat for XML parsing.
*
* @author    direct Netware Group
* @copyright (C) direct Netware Group - All rights reserved
* @package   XML.php
* @since     v0.1.00
* @license   http://www.direct-netware.de/redirect.php?licenses;mpl2
*            Mozilla Public License, v. 2.0
*/
class directXmlParserExpat
{
/**
	* @var boolean $data_merged_mode True if the parser is set to merged
	*      mode
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data_merged_mode;
/**
	* @var object $event_handler The EventHandler is called whenever debug messages
	*      should be logged or errors happened.
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $event_handler;
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
	* @param direct_xml_reader &$parser Container for the XML document
	* @param object $event_handler EventHandler to use
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __construct(&$parser, $event_handler = NULL)
	{
		if ($event_handler !== NULL) { $event_handler->debug("#echo(__FILEPATH__)# -XmlParser->__construct(directXmlParserExpat)- (#echo(__LINE__)#)"); }

/* -------------------------------------------------------------------------
Connect to the PHP container for the XML document 
------------------------------------------------------------------------- */

		$this->data_merged_mode = false;
		$this->event_handler = $event_handler;
		$this->node_path_array = array();
		$this->parser = $parser;
		$this->parser_active = false;
		$this->parser_strict_standard = true;
	}
/*#ifdef(PHP4):
/**
	* Constructor (PHP4) directXmlParserExpat
	*
	* @param direct_xml_reader &$parser Container for the XML document
	* @param object $event_handler EventHandler to use
	* @since v0.1.00
*\/
	function directXmlParserExpat(&$parser, $event_handler = NULL) { $this->__construct($parser, $event_handler); }
:#\n*/
/**
	* Destructor (PHP5+) __destruct (directXmlParserExpat)
	*
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __destruct() { $this->parser = NULL; }

/**
	* Define the parser mode ("tree" or "merged").
	*
	* @param  string $mode Mode to select
	* @return boolean True if parser is set to merged mode
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function defineMode($mode = "")
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->defineMode($mode)- (#echo(__LINE__)#)"); }

		if (!$this->parser_active && is_string($mode)) { $this->data_merged_mode = (($mode == "merged") ? true : false); }
		return $this->data_merged_mode;
	}

/**
	* Changes the parser mode regarding being strict standard conform.
	*
	* @param  boolean $strict_standard Be standard conform
	* @return boolean Accepted state
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function defineStrictStandard($strict_standard = true)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->defineStrictStandard(+strict_standard)- (#echo(__LINE__)#)"); }

		if (!$this->parser_active)
		{
			if ((is_bool($strict_standard) || is_string($strict_standard)) && $strict_standard) { $this->parser_strict_standard = true; }
			elseif ($strict_standard === NULL && !$this->parser_strict_standard) { $this->parser_strict_standard = true; }
			else { $this->parser_strict_standard = false; }
		}

		return $this->parser_strict_standard;
	}

/**
	* php.net: Character data handler is called for every piece of a text in the
	* XML document. It can be called multiple times inside each fragment (e.g.
	* for non-ASCII strings).
	*
	* @param object $parser XML parser calling this handler
	* @param string $data Character data
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function expatCData($parser, $data)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->expatCData(+parser, +data)- (#echo(__LINE__)#)"); }

		if ($this->parser_active)
		{
			if (isset($this->parser_cache[$this->parser_cache_link[$this->node_path]]['value'])) { $this->parser_cache[$this->parser_cache_link[$this->node_path]]['value'] .= $data; }
			else { $this->parser_cache[$this->parser_cache_link[$this->node_path]]['value'] = $data; }
		}
	}

/**
	* Method to handle "end element" callbacks.
	*
	* @param object $parser XML parser calling this handler
	* @param string $tag XML tag
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function expatElementEnd($parser, $tag)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->expatElementEnd(+parser, $tag)- (#echo(__LINE__)#)"); }

		if ($this->parser_active)
		{
			$node_path = $this->parser_cache_link[$this->node_path];

			unset($this->parser_cache_link[$this->node_path]);
			array_pop($this->node_path_array);
			$this->node_path_depth--;
			$this->node_path = implode(" ", $this->node_path_array);

			if (isset($this->parser_cache[$node_path]['value']))
			{
				if (isset($this->parser_cache[$node_path]['attributes']['xml:space']))
				{
					if ($this->parser_cache[$node_path]['attributes']['xml:space'] != "preserve") { $this->parser_cache[$node_path]['value'] = trim($this->parser_cache[$node_path]['value']); }
				}
				else { $this->parser_cache[$node_path]['value'] = trim($this->parser_cache[$node_path]['value']); }
			}
			else { $this->parser_cache[$node_path]['value'] = ""; }

			if (!$this->parser_strict_standard && isset($this->parser_cache[$node_path]['attributes']['value']) && !strlen($this->parser_cache[$node_path]['value']))
			{
				$this->parser_cache[$node_path]['value'] = $this->parser_cache[$node_path]['attributes']['value'];
				unset($this->parser_cache[$node_path]['attributes']['value']);
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
	* @param object $parser XML parser calling this handler
	* @param string $data Character data
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function expatMergedCData($parser, $data)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->expatMergedCData(+parser, +data)- (#echo(__LINE__)#)"); }

		if ($this->parser_active)
		{
			if ($this->parser_cache_link[$this->node_path] > 0) { $this->parser_cache[$this->node_path][$this->parser_cache_link[$this->node_path]]['value'] .= $data; }
			else { $this->parser_cache[$this->node_path]['value'] .= $data; }
		}
	}

/**
	* Method to handle "end element" callbacks. (Merged XML parser)
	*
	* @param object $parser XML parser calling this handler
	* @param string $tag XML tag
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function expatMergedElementEnd($parser, $tag)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->expatMergedElementEnd(+parser, $tag)- (#echo(__LINE__)#)"); }
	
		if ($this->parser_active)
		{
			if ($this->parser_cache_link[$this->node_path] > 0) { $node_ptr =& $this->parser_cache[$this->node_path][$this->parser_cache_link[$this->node_path]]; }
			else { $node_ptr =& $this->parser_cache[$this->node_path]; }

			array_pop($this->node_path_array);
			$this->node_path_depth--;
			$this->node_path = implode("_", $this->node_path_array);

			if (isset($node_ptr['attributes']['xml:space']))
			{
				if ($node_ptr['attributes']['xml:space'] != "preserve") { $node_ptr['value'] = trim($node_ptr['value']); }
			}
			else { $node_ptr['value'] = trim($node_ptr['value']); }

			if (isset($node_ptr['attributes']['value']) && (!strlen($node_ptr['value'])))
			{
				$node_ptr['value'] = $node_ptr['attributes']['value'];
				unset($node_ptr['attributes']['value']);
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
	* @param object $parser XML parser calling this handler
	* @param string $tag XML tag
	* @param array $attributes Node attributes
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function expatMergedElementStart($parser, $tag, $attributes)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->expatMergedElementStart(+parser, $tag, +attributes)- (#echo(__LINE__)#)"); }

		if (!$this->parser_active)
		{
			$this->node_path = "";
			$this->node_path_depth = 0;
			$this->parser_active = true;
			$this->parser_cache_link = array();
		}

		$tag = strtolower($tag);
		if (strpos($tag, "digitstart__") === 0) { $tag = substr($tag, 12); }

		if ($this->node_path) { $this->node_path .= "_"; }
		$this->node_path .= $tag;
		$this->node_path_array[] = $tag;
		$this->node_path_depth++;

		foreach ($attributes as $key => $value)
		{
			$key_lowercase = strtolower($key);

			if (strpos($key_lowercase, "xmlns:") === 0)
			{
				$ns_name = substr($key, 6);
				$attributes["xmlns:".$ns_name] = $value;
				if ($key != "xmlns:".$ns_name) { unset($attributes[$key]); }
			}
			elseif ($key_lowercase == "xml:space")
			{
				$attributes[$key_lowercase] = strtolower($value);
				if ($key != $key_lowercase) { unset($attributes[$key]); }
			}
			elseif ($key != $key_lowercase)
			{
				unset($attributes[$key]);
				$attributes[$key_lowercase] = $value;
			}
		}

		$node_array = array("tag" => $tag, "level" => $this->node_path_depth, "value" => "", "attributes" => $attributes);

		if (isset($this->parser_cache[$this->node_path]))
		{
			if (isset($this->parser_cache[$this->node_path]['tag'])) { $this->parser_cache[$this->node_path] = array($this->parser_cache[$this->node_path], $node_array); }
			else { $this->parser_cache[$this->node_path][] = $node_array; }

			$this->parser_cache_link[$this->node_path]++;
		}
		else
		{
			$this->parser_cache[$this->node_path] = $node_array;
			$this->parser_cache_link[$this->node_path] = 0;
		}
	}

/**
	* Method to handle "start element" callbacks.
	*
	* @param object $parser XML parser calling this handler
	* @param string $tag XML tag
	* @param array $attributes Node attributes
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function expatElementStart($parser, $tag, $attributes)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->expatElementStart(+parser, $tag, +attributes)- (#echo(__LINE__)#)"); }

		if (!$this->parser_active)
		{
			$this->node_path = "";
			$this->node_path_depth = 0;
			$this->parser_active = true;
			$this->parser_cache_counter = 0;
			$this->parser_cache_link = array();
		}

		if (!$this->parser_strict_standard)
		{
			$tag = strtolower($tag);
			if (strpos($tag, "digitstart__") === 0) { $tag = substr($tag, 12); }
		}

		if ($this->node_path) { $this->node_path .= " "; }
		$this->node_path .= $tag;
		$this->node_path_array[] = $tag;
		$this->node_path_depth++;

		foreach ($attributes as $key => $value)
		{
			$key_lowercase = strtolower($key);

			if (strpos($key_lowercase, "xmlns:") === 0)
			{
				$ns_name = substr($key, 6);
				$attributes["xmlns:".$ns_name] = $value;
				if ($key != "xmlns:".$ns_name) { unset($attributes[$key]); }
			}
			elseif ($key_lowercase == "xml:space")
			{
				$attributes[$key_lowercase] = strtolower($value);
				if ($key != $key_lowercase) { unset($attributes[$key]); }
			}
			elseif ((!$this->parser_strict_standard) && $key != $key_lowercase)
			{
				unset($attributes[$key]);
				$attributes[$key_lowercase] = $value;
			}
		}

		$this->parser_cache[] = array("node_path" => $this->node_path, "attributes" => $attributes);
		$this->parser_cache_link[$this->node_path] = $this->parser_cache_counter;
		$this->parser_cache_counter++;
	}

/**
	* Sets the EventHandler.
	*
	* @param object $event_handler EventHandler to use
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function setEventHandler($event_handler)
	{
		if ($event_handler !== NULL) { $event_handler->debug("#echo(__FILEPATH__)# -XmlParser->setEventHandler(+event_handler)- (#echo(__LINE__)#)"); }
		$this->event_handler = $event_handler;
	}

/**
	* Adds the result of an expat parsing operation to the defined XML instance
	* if the parser completed its work.
	*
	* @return array Multi-dimensional XML tree
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2arrayExpat()
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->xml2arrayExpat()- (#echo(__LINE__)#)"); }
		$return = array();

		if ((!$this->parser_active) && is_array($this->parser_cache) && (!empty($this->parser_cache)))
		{
			$this->parser->set(array());

			foreach ($this->parser_cache as $node_array) { $this->parser->nodeAdd($node_array['node_path'], $node_array['value'], $node_array['attributes']); }

			$this->parser_cache = array();
			$return = $this->parser->get();
		}

		return $return;
	}

/**
	* Returns the merged result of an expat parsing operation if the parser
	* completed its work.
	*
	* @return array Merged XML tree
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2arrayExpatMerged()
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->xml2arrayExpatMerged()- (#echo(__LINE__)#)"); }
		$return = array();

		if ((!$this->parser_active) && is_array($this->parser_cache) && (!empty($this->parser_cache)))
		{
			$return = $this->parser_cache;
			$this->parser_cache = array();
		}

		return $return;
	}
}

//j// EOF