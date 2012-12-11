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

use XMLReader;
/* #\n*/

/* -------------------------------------------------------------------------
All comments will be removed in the "production" packages (they will be in
all development packets)
------------------------------------------------------------------------- */

//j// Functions and classes

/**
* This implementation supports XMLReader for XML parsing.
*
* @author    direct Netware Group
* @copyright (C) direct Netware Group - All rights reserved
* @package   XML.php
* @since     v0.1.00
* @license   http://www.direct-netware.de/redirect.php?licenses;mpl2
*            Mozilla Public License, v. 2.0
*/
class directXmlParserXMLReader
{
/**
	* @var object $event_handler The EventHandler is called whenever debug messages
	*      should be logged or errors happened.
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $event_handler;
/**
	* @var array $node_types Node types that this parser knows
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $node_types;
/**
	* @var direct_xml_reader $parser Container for the XML document
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $parser;
/**
	* @var integer $timeout_retries Retries before timing out
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $timeout_retries;
/**
	* @var boolean $PHP_is_valid True if the "is_valid()" method is available
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $PHP_is_valid;

/* -------------------------------------------------------------------------
Construct the class using old and new behavior
------------------------------------------------------------------------- */

/**
	* Constructor (PHP5+) __construct (directXmlParserXMLReader)
	*
	* @param direct_xml_reader &$parser Container for the XML document
	* @param integer $timeout_retries Retries before timing out
	* @param object $event_handler EventHandler to use
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __construct(&$parser, $timeout_retries = 5, $event_handler = NULL)
	{
		if ($event_handler !== NULL) { $event_handler->debug("#echo(__FILEPATH__)# -XmlParser->__construct(directXmlParserXMLReader)- (#echo(__LINE__)#)"); }

/* -------------------------------------------------------------------------
Connect to the PHP container for the XML document 
------------------------------------------------------------------------- */

		$this->event_handler = $event_handler;
		$this->parser = $parser;
		$this->timeout_retries = $timeout_retries;

		if (defined("XMLREADER_ELEMENT"))
		{
$this->node_types = array(
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
$this->node_types = array(
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
	* @param direct_xml_reader &$parser Container for the XML document
	* @param integer $timeout_retries Retries before timing out
	* @param object $event_handler EventHandler to use
	* @since v0.1.00
*\/
	function directXmlParserXMLReader(&$parser, $timeout_retries = 5, $event_handler = NULL) { $this->__construct($parser, $timeout_retries, $event_handler); }
:#\n*/
/**
	* Destructor (PHP5+) __destruct (directXmlParserXMLReader)
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
	* Converts XML data into a multi-dimensional array ... using the
	* "simplexml_load_string()" result.
	*
	* @param  object &$xmlreader SimpleXMLElement object
	* @param  boolean $strict_standard Be standard conform
	* @return array Multi-dimensional XML tree
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2arrayXMLReader(&$xmlreader, $strict_standard = true)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->xml2arrayXMLReader(+xmlreader, +strict_standard)- (#echo(__LINE__)#)"); }
		$return = array();

		if ($this->XMLReaderIsValid($xmlreader))
		{
			$is_valid = true;
			$timeout_time = time() + $this->timeout_retries;
			$this->parser->set(array());

			do { $is_valid = $xmlreader->read(); }
			while ($is_valid && $xmlreader->nodeType != $this->node_types['element'] && time() < $timeout_time);

			$xmlreader_array = $this->XMLReaderWalker($xmlreader, $strict_standard);
			$xmlreader->close();

			if ($xmlreader_array) { $is_valid = $this->XMLReaderArrayWalker($xmlreader_array, $strict_standard); }
			if ($is_valid) { $return = $this->parser->get(); }
		}

		return $return;
	}

/**
	* Converts XML data into a merged array ... using the
	* "simplexml_load_string()" result.
	*
	* @param  object &$xmlreader SimpleXMLElement object
	* @return array Merged XML tree
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2arrayXMLReaderMerged(&$xmlreader)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->xml2arrayXMLReaderMerged(+xmlreader)- (#echo(__LINE__)#)"); }
		$return = array();

		if ($this->XMLReaderIsValid($xmlreader))
		{
			$depth = 0;
			$has_changed_node = false;
			$is_read = false;
			$is_valid = true;
			$node_path = "";
			$node_path_array = array();
			$nodes_array = array();
			$timeout_time = time() + $this->timeout_retries;

			do
			{
				switch ($xmlreader->nodeType)
				{
				case $this->node_types['cdata']:
				{
					if (isset($nodes_array[$node_path]['value'])) { $nodes_array[$node_path]['value'] .= ((isset($nodes_array[$node_path]['attributes']['xml:space']) && $nodes_array[$node_path]['attributes']['xml:space'] == "preserve") ? $xmlreader->value : trim($xmlreader->value)); }
					break 1;
				}
				case $this->node_types['element']:
				{
					$attributes_array = array();
					$node_name = strtolower($xmlreader->name);
					if (strpos($node_name, "digitstart__") === 0) { $node_name = substr($node_name, 12); }

					if ($xmlreader->attributeCount > 0)
					{
						if ($xmlreader->moveToFirstAttribute())
						{
							do
							{
								$attribute_name = strtolower($xmlreader->name);

								if (strpos($attribute_name, "xmlns:") === 0) { $attributes_array["xmlns:".substr($xmlreader->name, 6)] = $xmlreader->value; }
								elseif ($attribute_name == "xml:space") { $attributes_array['xml:space'] = strtolower($xmlreader->value); }
								else { $attributes_array[$attribute_name] = $xmlreader->value; }
							}
							while ($xmlreader->moveToNextAttribute() && time() < $timeout_time);

							$xmlreader->moveToElement();
						}
					}

					$depth = $xmlreader->depth;
					$has_changed_node = $xmlreader->isEmptyElement;
					$node_path_array = array_slice($node_path_array, 0, $depth);
					$node_path_array[] = $node_name;

					$node_path = implode("_", $node_path_array);
					$nodes_array[$node_path] = array("tag" => $node_name, "level" => $depth + 1, "value" => "", "attributes" => $attributes_array);

					$is_valid = $xmlreader->read();
					$is_read = true;

					break 1;
				}
				case $this->node_types['element_end']:
				{
					$has_changed_node = true;
					$is_read = true;
					$is_valid = $xmlreader->read();

					break 1;
				}
				case $this->node_types['text']:
				{
					if (isset($nodes_array[$node_path]['value'])) { $nodes_array[$node_path]['value'] .= ((isset($nodes_array[$node_path]['attributes']['xml:space']) && $nodes_array[$node_path]['attributes']['xml:space'] == "preserve") ? $xmlreader->value : trim($xmlreader->value)); }
					break 1;
				}
				}

				if ($has_changed_node)
				{
					$has_changed_node = false;

					if (!empty($nodes_array[$node_path]))
					{
						if (isset($nodes_array[$node_path]['attributes']['value']) && (!strlen($nodes_array[$node_path]['value'])))
						{
							$nodes_array[$node_path]['value'] = $nodes_array[$node_path]['attributes']['value'];
							unset($nodes_array[$node_path]['attributes']['value']);
						}

						if (empty($nodes_array[$node_path]['attributes'])) { unset($nodes_array[$node_path]['attributes']); }

						if (isset($return[$node_path]))
						{
							if (isset($return[$node_path]['tag']))
							{
								$node_packed_array = $return[$node_path];
								$return[$node_path] = array($node_packed_array);
								$node_packed_array = NULL;
							}

							$return[$node_path][] = $nodes_array[$node_path];
						}
						else { $return[$node_path] = $nodes_array[$node_path]; }

						unset($nodes_array[$node_path]);
					}

					$depth = $xmlreader->depth;
					array_pop($node_path_array);
					$node_path = implode("_", $node_path_array);
					$is_read = true;
				}
				elseif ($xmlreader->depth < $depth)
				{
					if (isset($nodes_array[$node_path])) { unset($nodes_array[$node_path]); }

					array_pop($node_path_array);
					$node_path = implode("_", $node_path_array);
					$depth = $xmlreader->depth;
				}

				if (!$is_read)
				{
					if ($is_valid) { $is_valid = $xmlreader->read(); }
				}
				else { $is_read = false; }
			}
			while ($is_valid && time() < $timeout_time);

			$xmlreader->close();
		}

		return $return;
	}

/**
	* Imports a pre-parsed XML array into the given parser instance.
	*
	* @param  array &$data Result array of a "XMLReaderWalker()"
	* @param  boolean $strict_standard Be standard conform
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function XMLReaderArrayWalker(&$data, $strict_standard = true)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->XMLReaderArrayWalker(+data, +strict_standard)- (#echo(__LINE__)#)"); }
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
				foreach ($data['children'] as $child_array) { $this->XMLReaderArrayWalker($child_array, $strict_standard); }
			}

			$return = true;
		}

		return $return;
	}

/**
	* Imports a pre-parsed XML array into the given parser instance.
	*
	* @param  array &$data Result array of a "XMLReaderWalker()"
	* @param  boolean $strict_standard Be standard conform
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function XMLReaderIsValid(&$xmlreader)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->XMLReaderIsValid(+xmlreader)- (#echo(__LINE__)#)"); }
		$return = is_object($xmlreader);

		if (!isset($this->PHP_is_valid)) { $this->PHP_is_valid = method_exists($xmlreader, "is_valid"); }
		if ($return && $this->PHP_is_valid) { $return = $xmlreader->is_valid(); }

		return $return;
	}

/**
	* Converts XML data into a multi-dimensional array using the recursive
	* algorithm.
	*
	* @param  object &$xmlreader SimpleXMLElement object
	* @param  boolean $strict_standard Be standard conform
	* @param  string $node_path Old node path (for recursive use only)
	* @param  integer $xml_level Current XML depth
	* @return mixed XML node array on success; false on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function XMLReaderWalker(&$xmlreader, $strict_standard = true, $node_path = "", $xml_level = 0)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -XmlParser->XMLReaderWalker(+xmlreader, +strict_standard, $node_path, $xml_level)- (#echo(__LINE__)#)"); }
		$return = false;

		if (is_object($xmlreader))
		{
			$attributes_array = array();
			$is_node = false;
			$is_valid = true;
			$node_content = "";
			$nodes_array = array();
			$preserve_value = false;
			$timeout_time = time() + $this->timeout_retries;

			while ((!$is_node) && $is_valid && time() < $timeout_time)
			{
				if ($xmlreader->nodeType == $this->node_types['element'])
				{
					if ($strict_standard) { $node_name = $xmlreader->name; }
					else
					{
						$node_name = strtolower($xmlreader->name);
						if (strpos($node_name, "digitstart__") === 0) { $node_name = substr($node_name, 12); }
					}

					if ($xmlreader->attributeCount > 0)
					{
						if ($xmlreader->moveToFirstAttribute())
						{
							do
							{
								$attribute_name = strtolower($xmlreader->name);

								if (strpos($attribute_name, "xmlns:") === 0) { $attributes_array["xmlns:".substr($xmlreader->name, 6)] = $xmlreader->value; }
								elseif ($attribute_name == "xml:space")
								{
									$attributes_array['xml:space'] = strtolower($xmlreader->value);
									if ($attributes_array['xml:space'] == "preserve") { $preserve_value = true; }
								}
								elseif (!$strict_standard) { $attributes_array[$attribute_name] = $xmlreader->value; }
								else { $attributes_array[$xmlreader->name] = $xmlreader->value; }
							}
							while ($xmlreader->moveToNextAttribute() && time() < $timeout_time);

							$xmlreader->moveToElement();
						}
					}

					$is_node = true;
				}

				$is_valid = $xmlreader->read();
			}

			if ($is_node)
			{
				if (strlen($node_path)) { $node_path = $node_path." ".$node_name; }
				else { $node_path = $node_name; }
			}

			while ($is_node && time() < $timeout_time)
			{
				if ($xml_level < $xmlreader->depth)
				{
					switch ($xmlreader->nodeType)
					{
					case $this->node_types['cdata']:
					{
						$node_content .= (($preserve_value) ? $xmlreader->value : trim($xmlreader->value));
						break 1;
					}
					case $this->node_types['element']:
					{
						$nodes_array[] = $this->XMLReaderWalker($xmlreader, $strict_standard, $node_path, $xmlreader->depth);
						$is_valid = false;
						break 1;
					}
					case $this->node_types['element_end']:
					{
						$is_valid = false;
						if (!$xmlreader->read()) { $is_node = false; }
						break 1;
					}
					case $this->node_types['text']:
					{
						$node_content .= (($preserve_value) ? $xmlreader->value : trim($xmlreader->value));
						break 1;
					}
					default:
					{
						if ($preserve_value && ($xmlreader->nodeType == $this->node_types['whitespace'] || $xmlreader->nodeType == $this->node_types['whitespace_significant'])) { $node_content .= $xmlreader->value; }
					}
					}

					if ($is_valid)
					{
						if ($is_node) { $is_node = $xmlreader->read(); }
						else { $is_node = $xmlreader->read(); }
					}
					else { $is_valid = true; }
				}
				else { $is_node = false; }
			}

			$return = array("node_path" => $node_path, "value" => $node_content, "attributes" => $attributes_array, "children" => $nodes_array);
		}

		return $return;
	}
}

//j// EOF