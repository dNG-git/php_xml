<?php
//j// BOF

/*n// NOTE
----------------------------------------------------------------------------
XML.php
Multiple XML parsers with a common abstraction layer
----------------------------------------------------------------------------
(C) direct Netware Group - All rights reserved
http://www.direct-netware.de/redirect.php?ext_core_xml

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
* This implementation supports SimpleXML for XML parsing.
*
* @author    direct Netware Group
* @copyright (C) direct Netware Group - All rights reserved
* @package   XML.php
* @since     v0.1.00
* @license   http://www.direct-netware.de/redirect.php?licenses;mpl2
*            Mozilla Public License, v. 2.0
*/
class directXmlParserSimpleXML
{
/**
	* @var object $event_handler The EventHandler is called whenever debug messages
	*      should be logged or errors happened.
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $event_handler;
/**
	* @var direct_xml_reader $parser Container for the XML document
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $parser;
/**
	* @var integer $timeout_retries Retries before timing out
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $timeout_retries;

/* -------------------------------------------------------------------------
Construct the class using old and new behavior
------------------------------------------------------------------------- */

/**
	* Constructor (PHP5+) __construct (directXmlParserSimpleXML)
	*
	* @param direct_xml_reader &$parser Container for the XML document
	* @param integer $timeout_retries Retries before timing out
	* @param object $event_handler EventHandler to use
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __construct(&$parser, $timeout_retries = 5, $event_handler = NULL)
	{
		if ($event_handler !== NULL) { $event_handler->debug("#echo(__FILEPATH__)# -XmlParser->__construct(directXmlParserSimpleXML)- (#echo(__LINE__)#)"); }

/* -------------------------------------------------------------------------
Connect to the PHP container for the XML document 
------------------------------------------------------------------------- */

		$this->event_handler = $event_handler;
		$this->parser = $parser;
		$this->timeout_retries = $timeout_retries;
	}
/*#ifdef(PHP4):
/**
	* Constructor (PHP4) directXmlParserSimpleXML
	*
	* @param direct_xml_reader &$parser Container for the XML document
	* @param integer $timeout_retries Retries before timing out
	* @param object $event_handler EventHandler to use
	* @since v0.1.00
*\/
	function directXmlParserSimpleXML(&$parser, $timeout_retries = 5, $event_handler = NULL) { $this->__construct($parser, $timeout_retries, $event_handler); }
:#\n*/
/**
	* Destructor (PHP5+) __destruct (directXmlParserSimpleXML)
	*
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __destruct() { $this->parser = NULL; }

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
	* Imports a pre-parsed XML array into the given parser instance.
	*
	* @param  array &$data Result array of a "SimpleXMLWalker()"
	* @param  boolean $strict_standard Be standard conform
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function SimpleXMLArrayWalker(&$data, $strict_standard = true)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->SimpleXMLArrayWalker(+data, +strict_standard)- (#echo(__LINE__)#)"); }
		$return = false;

		if (is_array($data))
		{
			if (strlen($data['value']) || (!empty($data['attributes'])) || (!empty($data['children'])))
			{
				if ((!$strict_standard) && isset($data['attributes']['value']) && (!strlen($data['value'])))
				{
					$data['value'] = $data['attributes']['value'];
					unset($data['attributes']['value']);
				}

				$this->parser->nodeAdd($data['node_path'], $data['value'], $data['attributes']);
			}

			if (!empty($data['children']))
			{
				foreach ($data['children'] as $child_array) { $this->SimpleXMLArrayWalker($child_array, $strict_standard); }
			}

			$return = true;
		}

		return $return;
	}

/**
	* Converts a pre-parsed XML array into a merged array.
	*
	* @param  array &$data Result array of a "SimpleXMLWalker()"
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function SimpleXMLMergedArrayWalker(&$data, $return = NULL)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->SimpleXMLMergedArrayWalker(+data, +return)- (#echo(__LINE__)#)"); }
		if (!isset($return)) { $return = array(); }

		if (is_array($data))
		{
			if (strlen($data['value']) || (!empty($data['attributes'])))
			{
				$node_array = array ("tag" => substr($data['node_path'], strrpos($data['node_path'], " ")), "value" => $data['value'], "attributes" => $data['attributes']);

				if (isset($data['attributes']['value']) && (!strlen($data['value'])))
				{
					$node_array['value'] = $data['attributes']['value'];
					unset($node_array['attributes']['value']);
				}

				$node_path = str_replace(" ", "_", $data['node_path']);

				if (isset($return[$node_path]))
				{
					if (isset($return[$node_path]['tag']))
					{
						$node_packed_array = $return[$node_path];
						$return[$node_path] = array($node_packed_array);
						$node_packed_array = NULL;
					}

					$return[$node_path][] = $node_array;
				}
				else { $return[$node_path] = $node_array; }
			}

			if (isset($data['children']))
			{
				foreach ($data['children'] as $child_array) { $return = $this->SimpleXMLMergedArrayWalker($child_array, $return); }
			}
		}

		return $return;
	}

/**
	* Converts XML data into a multi-dimensional array using the recursive
	* algorithm.
	*
	* @param  object &$xmlelement SimpleXMLElement object
	* @param  boolean $strict_standard Be standard conform
	* @param  string $node_path Old node path (for recursive use only)
	* @param  integer $xml_level Current XML depth
	* @return mixed XML node array on success; false on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function SimpleXMLWalker(&$xmlelement, $strict_standard = true, $node_path = "", $xml_level = 0)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->SimpleXMLWalker(+xmlreader, +strict_standard, $node_path, $xml_level)- (#echo(__LINE__)#)"); }
		$return = false;

		if (is_object($xmlelement))
		{
			$attributes_array = array();
			$preserve_value = false;
			$xmlelement_subnode = NULL;

			if ($strict_standard) { $node_name = $xmlelement->getName(); }
			else
			{
				$node_name = strtolower($xmlelement->getName());
				if (strpos($node_name, "digitstart__") === 0) { $node_name = substr($node_name, 12); }
			}

			if (strlen($node_path)) { $node_path = $node_path." ".$node_name; }
			else { $node_path = $node_name; }

			$xmlelement_attributes = $xmlelement->attributes();

			if ($xmlelement_attributes === NULL || empty($xmlelement_attributes)) { $attributes_array = array(); }
			else
			{
				$attributes_array = array();

				foreach ($xmlelement_attributes as $attribute => $value)
				{
					$attribute_name = strtolower($attribute);

					if (strpos($attribute_name, "xmlns:") === 0) { $attributes_array["xmlns:".substr($attribute, 6)] = (string)$value; }
					elseif ($attribute_name == "xml:space")
					{
						$attributes_array['xml:space'] = strtolower((string)$value);
						if ($attributes_array['xml:space'] == "preserve") { $preserve_value = true; }
					}
					elseif (!$strict_standard) { $attributes_array[$attribute_name] = (string)$value; }
					else { $attributes_array[$attribute] = (string)$value; }
				}
			}

			$is_timed_out = false;
			$nodes_array = array();
			$timeout_time = time() + $this->timeout_retries;
			$xmlelement_subnodes = $xmlelement->children();

			foreach ($xmlelement_subnodes as $xmlelement_subnode)
			{
				if ((!$is_timed_out) && time() < $timeout_time) { $nodes_array[] = $this->SimpleXMLWalker($xmlelement_subnode, $strict_standard, $node_path, $xml_level + 1); }
				else { $is_timed_out = true; }
			}

			if ($is_timed_out && $this->event_handler !== NULL) { $this->event_handler->error("#echo(__FILEPATH__)# -XmlParser->SimpleXMLWalker()- timed out"); }
			$return = array("node_path" => $node_path, "value" => ($preserve_value ? (string)$xmlelement : trim((string)$xmlelement)), "attributes" => $attributes_array, "children" => $nodes_array);
		}

		return $return;
	}

/**
	* Converts XML data into a multi-dimensional array ... using the
	* "simplexml_load_string()" result.
	*
	* @param  object &$xmlelement SimpleXMLElement object
	* @param  boolean $strict_standard Be standard conform
	* @return array Multi-dimensional XML tree
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2arraySimpleXML(&$xmlelement, $strict_standard = true)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->xml2arraySimpleXML(+xmlreader, +strict_standard)- (#echo(__LINE__)#)"); }
		$return = array();

		if (is_object($xmlelement))
		{
			$this->parser->set(array());
			$xmlelement_array = $this->SimpleXMLWalker($xmlelement, $strict_standard);

			if ($xmlelement_array) { $is_valid = $this->SimpleXMLArrayWalker($xmlelement_array, $strict_standard); }
			if ($is_valid) { $return = $this->parser->get(); }
		}

		return $return;
	}

/**
	* Converts XML data into a merged array ... using the
	* "simplexml_load_string()" result.
	*
	* @param  object &$xmlelement SimpleXMLElement object
	* @return array Merged XML tree
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2arraySimpleXMLMerged(&$xmlelement)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->xml2arraySimpleXMLMerged(+xmlreader)- (#echo(__LINE__)#)"); }
		$return = array();

		if (is_object($xmlelement))
		{
			$xmlelement_array = $this->SimpleXMLWalker($xmlelement, false);
			if ($xmlelement_array) { $return = $this->SimpleXMLMergedArrayWalker($xmlelement_array); }
		}

		return $return;
	}
}

//j// EOF