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
* This class provides a bridge between PHP and XML to read XML on the fly.
*
* @author    direct Netware Group
* @copyright (C) direct Netware Group - All rights reserved
* @package   XML.php
* @since     v0.1.00
* @license   http://www.direct-netware.de/redirect.php?licenses;mpl2
*            Mozilla Public License, v. 2.0
*/
class directXmlParser
{
/**
	* @var array $data XML data
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data;
/**
	* @var string $data_cache_node Path of the cached node pointer
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data_cache_node;
/**
	* @var mixed $data_cache_ptr Reference of the cached node pointer
	*      (string if unset)
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data_cache_ptr;
/**
	* @var string $data_charset Charset used
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data_charset;
/**
	* @var array $data_ns Cache for known XML NS (URI)
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data_ns;
/**
	* @var array $data_ns_compact Cache for the compact number of a XML NS
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data_ns_compact;
/**
	* @var integer $data_ns_compact Counter for the compact link numbering
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data_ns_counter;
/**
	* @var array $data_ns_default Cache for the XML NS and the
	*      corresponding number
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data_ns_default;
/**
	* @var array $data_ns_predefined_default Cache of node pathes with a
	*      predefined NS (key = Compact name)
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data_ns_predefined_compact;
/**
	* @var array $data_ns_predefined_default Cache of node pathes with a
	*      predefined NS (key = Full name)
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data_ns_predefined_default;
/**
	* @var boolean $data_parse_only Parse data only
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data_parse_only;
/**
	* @var object $data_parser The selected parser implementation
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data_parser;
/**
	* @var object $data_parser_name The selected parser implementation name
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data_parser_name;
/**
	* @var object $event_handler The EventHandler is called whenever debug messages
	*      should be logged or errors happened.
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $event_handler;

/* -------------------------------------------------------------------------
Construct the class using old and new behavior
------------------------------------------------------------------------- */

/**
	* Constructor (PHP5+) __construct (directXmlParser)
	*
	* @param string $charset Charset to be added as information to XML output
	* @param boolean $parse_only Parse data only
	* @param integer $timeout_retries Retries before timing out
	* @param object $event_handler EventHandler to use
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __construct($charset = "UTF-8", $parse_only = true, $timeout_retries = 5, $event_handler = NULL)
	{
		if ($event_handler !== NULL) { $event_handler->debug("#echo(__FILEPATH__)# -Xml->__construct(directXmlParser)- (#echo(__LINE__)#)"); }

/* -------------------------------------------------------------------------
Force or automatically select an implemenation 
------------------------------------------------------------------------- */

		$this->data_parser = NULL;

		if (USE_xml_implementation == "autoselect" || USE_xml_implementation == "expat")
		{
			if (function_exists("xml_parser_create") && class_exists(/*#ifdef(PHP5n) */'dNG\data\directXmlParserExpat'/* #*//*#ifndef(PHP5n):"directXmlParserExpat":#*/)) { $this->data_parser = new directXmlParserExpat($this, $event_handler); }
			if ($this->data_parser !== NULL) { $this->data_parser_name = "expat"; }
		}

		if (USE_xml_implementation == "autoselect" && ($this->data_parser === NULL || USE_xml_implementation == "SimpleXML"))
		{
			if (function_exists("simplexml_load_string") && class_exists(/*#ifdef(PHP5n) */'dNG\data\directXmlParserSimpleXML'/* #*//*#ifndef(PHP5n):"directXmlParserSimpleXML":#*/)) { $this->data_parser = new directXmlParserSimpleXML($this, $timeout_retries, $event_handler); }
			if ($this->data_parser !== NULL) { $this->data_parser_name = "SimpleXML"; }
		}

		if (USE_xml_implementation == "autoselect" && ($this->data_parser === NULL || USE_xml_implementation == "XMLReader"))
		{
			if (class_exists("XMLReader"/*#ifndef(PHP4) */, false/* #*/) && class_exists(/*#ifdef(PHP5n) */'dNG\data\directXmlParserXMLReader'/* #*//*#ifndef(PHP5n):"directXmlParserXMLReader":#*/)) { $this->data_parser = new directXmlParserXMLReader($this, $timeout_retries, $event_handler); }
			if ($this->data_parser !== NULL) { $this->data_parser_name = "XMLReader"; }
		}

/* -------------------------------------------------------------------------
Initiate the array tree cache
------------------------------------------------------------------------- */

		$this->data = array();
		$this->data_cache_node = "";
		$this->data_cache_ptr = NULL;
		$this->data_charset = strtoupper($charset);
		$this->data_ns = array();
		$this->data_ns_compact = array();
		$this->data_ns_counter = 0;
		$this->data_ns_default = array();
		$this->data_ns_predefined_compact = array();
		$this->data_ns_predefined_default = array();
		$this->data_parse_only = $parse_only;
		$this->event_handler = $event_handler;
	}
/*#ifdef(PHP4):
/**
	* Constructor (PHP4) directXmlParser
	*
	* @param string $charset Charset to be added as information to XML output
	* @param boolean $parse_only Parse data only
	* @param integer $timeout_retries Retries before timing out
	* @param object $event_handler EventHandler to use
	* @since v0.1.00
*\/
	function directXmlParser($charset = "UTF-8", $parse_only = true, $timeout_retries = 5, $event_handler = NULL) { $this->__construct($charset, $parse_only, $timeout_retries, $event_handler); }
:#\n*/
/**
	* Destructor (PHP5+) __destruct (directXmlParser)
	*
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __destruct() { $this->data_parser = NULL; }

/**
	* Builds recursively a valid XML ouput reflecting the given XML array tree.
	*
	* @param  array &$xml_array XML array tree level to work on
	* @param  boolean $strict_standard Be standard conform
	* @return string XML output string
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function array2xml(&$xml_array, $strict_standard = true)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->array2xml(+xml_array, +strict_standard)- (#echo(__LINE__)#)"); }
		$return = "";

		if (is_array($xml_array) && !empty($xml_array))
		{
			foreach ($xml_array as $xml_node_array)
			{
				if (isset($xml_node_array['xml.mtree']))
				{
					unset($xml_node_array['xml.mtree']);
					$return .= $this->array2xml($xml_node_array, $strict_standard);
				}
				elseif (isset($xml_node_array['xml.item']))
				{
					$return .= $this->array2xmlItemEncoder($xml_node_array['xml.item'], false, $strict_standard);

					if (preg_match("#^\d#", $xml_node_array['xml.item']['tag'])) { $xml_node_tag = "digitstart__".$xml_node_array['xml.item']['tag']; }
					else { $xml_node_tag = $xml_node_array['xml.item']['tag']; }

					unset($xml_node_array['xml.item']);
					$return .= $this->array2xml($xml_node_array, $strict_standard);

					$return .= "</$xml_node_tag>";
				}
				elseif (strlen($xml_node_array['tag'])) { $return .= $this->array2xmlItemEncoder($xml_node_array, true, $strict_standard); }
			}
		}

		return trim($return);
	}

/**
	* Builds recursively a valid XML ouput reflecting the given XML array tree.
	*
	* @param  array $data Array containing information about the current item
	* @param  boolean $close_tag Output will contain a ending tag if true
	* @param  boolean $strict_standard Be standard conform
	* @return string XML output string
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function array2xmlItemEncoder($data, $close_tag = true, $strict_standard = true)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->array2xmlItemEncoder(+data, +close_tag, +strict_standard)- (#echo(__LINE__)#)"); }
		$return = "";

		$value_as_attribute = ($strict_standard ? false : true);

		if (is_array($data))
		{
			if (strlen($data['tag']))
			{
				if (preg_match("#^\d#", $data['tag'])) { $data['tag'] = "digitstart__".$data['tag']; }
				$return .= "<".$data['tag'];

				if (isset($data['attributes']))
				{
					foreach ($data['attributes'] as $key => $value)
					{
						if (!$strict_standard && $key == "value" && !strlen($data['value'])) { $data['value'] = $value; }
						else
						{
							$value = str_replace(array("&", "<", ">", '"'), array("&amp;", "&lt;", "&gt;", "&quot;"), $value);
							if ($this->data_charset != "UTF-8") { $value = mb_convert_encoding($value, $this->data_charset, "UTF-8"); }

							$return .= " $key=\"$value\"";
						}
					}
				}

				if (isset($data['value']) && ($strict_standard || strlen($data['value'])))
				{
					if ($value_as_attribute)
					{
						if (strpos($data['value'], "&") !== false) { $value_as_attribute = false; }
						elseif (strpos($data['value'], "<") !== false) { $value_as_attribute = false; }
						elseif (strpos($data['value'], ">") !== false) { $value_as_attribute = false; }
						elseif (strpos($data['value'], '"') !== false) { $value_as_attribute = false; }
						elseif (preg_match("#\s#", str_replace(" ", "_", $data['value']))) { $value_as_attribute = false; }
					}

					if ($value_as_attribute)
					{
						if ($this->data_charset != "UTF-8") { $data['value'] = mb_convert_encoding($data['value'], $this->data_charset, "UTF-8"); }
						$return .= " value=\"$data[value]\"";
					}
				}

				if ($value_as_attribute && $close_tag) { $return .= " />"; }
				else
				{
					$return .= ">";

					if (isset($data['value']) && !$value_as_attribute)
					{
						if ($this->data_charset != "UTF-8") { $data['value'] = mb_convert_encoding($data['value'], $this->data_charset, "UTF-8"); }

						if (strpos($data['value'], "<") === false && strpos($data['value'], ">") === false)
						{
							$data['value'] = str_replace("&", "&amp;", $data['value']);
							$return .= $data['value'];
						}
						else
						{
							if (strpos($data['value'], "]]>") !== false) { $data['value'] = str_replace("]]>", "]]]]><![CDATA[>", $data['value']); }
							$return .= "<![CDATA[{$data['value']}]]>";
						}
					}
				}

				if (!$value_as_attribute && $close_tag) { $return .= "</$data[tag]>"; }
			}
		}

		return $return;
	}

/**
	* Changes the object behaviour of deleting cached data after parsing is
	* completed.
	*
	* @param  boolean $parse_only Parse data only
	* @return boolean Accepted state
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function defineParseOnly($parse_only = true)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->defineParseOnly(+parse_only)- (#echo(__LINE__)#)"); }

		if ((is_bool($parse_only) || is_string($parse_only)) && $parse_only) { $this->data_parse_only = true; }
		elseif ($parse_only === NULL && !$this->data_parse_only) { $this->data_parse_only = true; }
		else { $this->data_parse_only = false; }

		return $this->data_parse_only;
	}

/**
	* This operation just gives back the content of $this->data.
	*
	* @return mixed XML data on success; false on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function get()
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->get()- (#echo(__LINE__)#)"); }

		if (isset($this->data)) { return $this->data; }
		else { return false; }
	}

/**
	* Adds a XML node with content - recursively if required.
	*
	* @param  string $node_path Path to the new node - delimiter is space
	* @param  string $value Value for the new node
	* @param  array $attributes Attributes of the node
	* @param  boolean $add_recursively True to create the required tree
	*         recursively
	* @return boolean False on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nodeAdd($node_path, $value = "", $attributes = "", $add_recursively = true)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->nodeAdd($node_path, +value, +attributes, +add_recursively)- (#echo(__LINE__)#)"); }
		$return = false;

		if (is_string($node_path) && !is_array($value) && !is_object($value))
		{
			$node_path = $this->nsTranslatePath($node_path);

			if (strlen($this->data_cache_node) && /*#ifndef(PHP4) */stripos($this->data_cache_node, $node_path) === 0/* #*//*#ifdef(PHP4):preg_match("#^".preg_quote($node_path, "#")."#i", $this->data_cache_node):#*/)
			{
				$node_path = trim(substr($node_path, strlen($this->data_cache_node)));
				$node_path_done = $this->data_cache_node;
				$node_ptr =& $this->data_cache_ptr;
			}
			else
			{
				$node_path_done = "";
				$node_ptr =& $this->data;
			}

			$nodes_array = explode(" ", $node_path);
			$is_valid = true;

			while ($is_valid && !empty($nodes_array))
			{
				$is_valid = false;
				$node_name = array_shift($nodes_array);

				if (preg_match("#^(.+?)\#(\d+)$#", $node_name, $result_array))
				{
					$node_name = $result_array[1];
					$node_position = $result_array[2];
				}
				else { $node_position = -1; }

				if (empty($nodes_array))
				{
					$node_array = array("tag" => $node_name, "value" => $value, "xmlns" => array());
					$node_has_ns = true;
					$node_ns_name = "";
					if (isset($node_ptr['xml.item']['xmlns'])) { $node_array['xmlns'] = $node_ptr['xml.item']['xmlns']; }

					if (is_array($attributes) && !empty($attributes))
					{
						if (isset($attributes['xmlns']))
						{
							if (strlen($attributes['xmlns']))
							{
								if (isset($this->data_ns_default[$attributes['xmlns']]))
								{
									$node_array['xmlns']['@'] = $this->data_ns_default[$attributes['xmlns']];
									$node_ns_name = $this->data_ns_default[$attributes['xmlns']].":".$node_name;
								}
								else
								{
									$this->data_ns_counter++;
									$this->data_ns_default[$attributes['xmlns']] = $this->data_ns_counter;
									$this->data_ns_compact[$this->data_ns_counter] = $attributes['xmlns'];
									$node_array['xmlns']['@'] = $this->data_ns_counter;
									$node_ns_name = $this->data_ns_counter.":".$node_name;
								}
							}
							elseif (isset($node_array['xmlns']['@'])) { unset($node_array['xmlns']['@']); }
						}

						foreach ($attributes as $key => $value)
						{
							if (/*#ifndef(PHP4) */stripos($key, "xmlns:") === 0/* #*//*#ifdef(PHP4):preg_match("#^xmlns\:#i", $key):#*/)
							{
								$ns_name = substr($key, 6);

								if (strlen($value)) { $node_array['xmlns'][$ns_name] = (isset($this->data_ns_default[$value]) ? $this->data_ns_default[$value] : $value); }
								elseif (isset($node_array['xmlns'][$ns_name])) { unset($node_array['xmlns'][$ns_name]); }
							}
						}

						$node_array['attributes'] = $attributes; 
					}

					if (preg_match("#^(.+?):(\w+)$#", $node_name, $result_array))
					{
						if (is_numeric($node_array['xmlns'][$result_array[1]])) { $node_ns_name = $node_array['xmlns'][$result_array[1]].":".$result_array[2]; }
						else { $node_has_ns = false; }
					}
					elseif (isset($node_array['xmlns']['@'])) { $node_ns_name = $node_array['xmlns']['@'].":".$node_name; }
					else { $node_has_ns = false; }

					if (strlen($node_path_done))
					{
						if ($node_has_ns) { $this->data_ns_predefined_compact[$node_path_done." ".$node_name] = (isset($this->data_ns_predefined_compact[$node_path_done]) ? $this->data_ns_predefined_compact[$node_path_done] : $node_path_done)." ".$node_ns_name; }
						else { $this->data_ns_predefined_compact[$node_path_done." ".$node_name] = $this->data_ns_predefined_compact[$node_path_done]." ".$node_name; }

						$this->data_ns_predefined_default[$this->data_ns_predefined_compact[$node_path_done." ".$node_name]] = $node_path_done." ".$node_name;
					}
					elseif ($node_has_ns)
					{
						$this->data_ns_predefined_compact[$node_name] = $node_ns_name;
						$this->data_ns_predefined_default[$node_ns_name] = $node_name;
					}
					else
					{
						$this->data_ns_predefined_compact[$node_name] = $node_name;
						$this->data_ns_predefined_default[$node_name] = $node_name;
					}

					if (isset($node_ptr[$node_name]))
					{
						if (isset($node_ptr[$node_name]['xml.mtree']))
						{
							$node_ptr[$node_name]['xml.mtree']++;
							$node_ptr[$node_name][] = $node_array;
						}
						else { $node_ptr[$node_name] = array("xml.mtree" => 1, $node_ptr[$node_name], $node_array); }
					}
					else { $node_ptr[$node_name] = $node_array; }

					$return = true;
				}
				else
				{
					if (isset($node_ptr[$node_name]))
					{
						if (isset($node_ptr[$node_name]['xml.mtree']))
						{
							if ($node_position >= 0)
							{
								if (isset($node_ptr[$node_name][$node_position]))
								{
									$is_valid = true;
									$return = true;

									if (!isset($node_ptr[$node_name][$node_position]['xml.item'])) { $node_ptr[$node_name][$node_position] = array("xml.item" => $node_ptr[$node_name][$node_position]); }
									$node_ptr =& $node_ptr[$node_name][$node_position];
								}
							}
							elseif (isset($node_ptr[$node_name][$node_ptr[$node_name]['xml.mtree']]))
							{
								$is_valid = true;
								$return = true;

								if (!isset($node_ptr[$node_name][$node_ptr[$node_name]['xml.mtree']]['xml.item'])) { $node_ptr[$node_name][$node_ptr[$node_name]['xml.mtree']] = array("xml.item" => $node_ptr[$node_name][$node_ptr[$node_name]['xml.mtree']]); }
								$node_ptr =& $node_ptr[$node_name][$node_ptr[$node_name]['xml.mtree']];
							}
						}
						elseif (isset($node_ptr[$node_name]['xml.item']))
						{
							$is_valid = true;
							$node_ptr =& $node_ptr[$node_name];
						}
						else
						{
							$is_valid = true;
							$node_ptr[$node_name]['level'] = (isset($node_ptr['xml.item']['level']) ? (1 + $node_ptr['xml.item']['level']) : 1);
							$node_ptr[$node_name] = array("xml.item" => $node_ptr[$node_name]);
							$node_ptr =& $node_ptr[$node_name];
						}
					}

					if ((!$is_valid) && $add_recursively)
					{
						$is_valid = true;
						$node_level = (isset($node_ptr['xml.item']['level']) ? (1 + $node_ptr['xml.item']['level']) : 1);
						$node_array = array("tag" => $node_name, "level" => $node_level, "xmlns" => array());
						if (isset($node_ptr['xml.item']['xmlns'])) { $node_array['xmlns'] = $node_ptr['xml.item']['xmlns']; }

						$node_ptr[$node_name] = array("xml.item" => $node_array);
						$node_ptr =& $node_ptr[$node_name];
					}

					if ($node_path_done) { $node_path_done .= " "; }
					$node_path_done .= $node_name;
				}
			}
		}

		return $return;
	}

/**
	* Registers a namespace (URI) for later use with this XML bridge class.
	*
	* @param string $ns Output relevant namespace definition
	* @param string $uri Uniform Resource Identifier
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nsRegister($ns, $uri)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->nsRegister($ns, $uri)- (#echo(__LINE__)#)"); }
		$this->data_ns[$ns] = $uri;

		if (!isset($this->data_ns_default[$uri]))
		{
			$this->data_ns_counter++;
			$this->data_ns_default[$uri] = $this->data_ns_counter;
			$this->data_ns_compact[$this->data_ns_counter] = $uri;
		}
	}

/**
	* Translates the tag value if a predefined namespace matches. The translated
	* tag will be saved as "tag_ns" and "tag_parsed".
	*
	* @param  array $node XML array node
	* @return array Checked XML array node
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nsTranslate($node)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->nsTranslate(+node)- (#echo(__LINE__)#)"); }
		$return = $node;

		if (is_array($node) && isset($node['xmlns']) && is_array($node['xmlns']) && isset($node['tag']))
		{
			$return['tag_ns'] = "";
			$return['tag_parsed'] = $node['tag'];

			if (preg_match("#^(.+?):(\w+)$#", $node['tag'], $result_array) && isset($node['xmlns'][$result_array[1]]/*#ifndef(PHP4) */, /* #*//*#ifdef(PHP4):) && isset(:#*/$this->data_ns_compact[$node['xmlns'][$result_array[1]]]))
			{
				$tag_ns = array_search($this->data_ns_compact[$node['xmlns'][$result_array[1]]], $this->data_ns);

				if ($tag_ns)
				{
					$return['tag_ns'] = $tag_ns;
					$return['tag_parsed'] = $tag_ns.":".$result_array[2];
				}
			}

			if (isset($node['attributes']))
			{
				foreach ($node['attributes'] as $key => $value)
				{
					if (preg_match("#^(.+?):(\w+)$#", $key, $result_array) && isset($node['xmlns'][$result_array[1]]/*#ifndef(PHP4) */, /* #*//*#ifdef(PHP4):) && isset(:#*/$this->data_ns_compact[$node['xmlns'][$result_array[1]]]))
					{
						$tag_ns = array_search($this->data_ns_compact[$node['xmlns'][$result_array[1]]], $this->data_ns);

						if ($tag_ns)
						{
							$return['attributes'][($tag_ns.":".$result_array[2])] = $value;
							unset($return['attributes'][$key]);
						}
					}
				}
			}
		}

		return $return;
	}

/**
	* Checks input path for predefined namespaces converts it to the internal
	* path.
	*
	* @param  string $node_path Path to the new node - delimiter is space
	* @return string Output node path
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function nsTranslatePath($node_path)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->nsTranslatePath($node_path)- (#echo(__LINE__)#)"); }

		$nodes_array = explode(" ", $node_path);
		$return = $node_path;
		$node_path = "";

		while (!empty($nodes_array))
		{
			$node_name = array_shift($nodes_array);
			if ($node_path) { $node_path .= " "; }

			if (strpos($node_name, ":") === false) { $node_path .= $node_name; }
			else
			{
				if (preg_match("#^(.+?):(\w+)$#", $node_name, $result_array))
				{
					if (isset($this->data_ns[$result_array[1]])) { $node_path .= (isset($this->data_ns_default[$this->data_ns[$result_array[1]]]) ? $this->data_ns_default[$this->data_ns[$result_array[1]]] : $result_array[1]).":".$result_array[2]; }
					else { $node_path .= $result_array[1].":".$result_array[2]; }
				}
				else { $node_path .= $node_name; }
			}
		}

		if (isset($this->data_ns_predefined_default[$node_path])) { $return = $this->data_ns_predefined_default[$node_path]; }
		return $return;
	}

/**
	* Unregisters a namespace or clears the cache (if $ns is empty).
	*
	* @param string $ns Output relevant namespace definition
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nsUnregister($ns = "")
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->nsUnregister($ns)- (#echo(__LINE__)#)"); }

		if (strlen($ns))
		{
			if (isset($this->data_ns[$ns]))
			{
				unset($this->data_ns_compact[$this->data_ns_default[$this->data_ns[$ns]]]);
				unset($this->data_ns_default[$this->data_ns[$ns]]);
				unset($this->data_ns[$ns]);
			}
		}
		else
		{
			$this->data_ns = array();
			$this->data_ns_compact = array();
			$this->data_ns_counter = 0;
			$this->data_ns_default = array();
			$this->data_ns_predefined_compact = array();
			$this->data_ns_predefined_default = array();
		}
	}

/**
	* "Imports" a XML tree into the cache.
	*
	* @param  array $xml_array Input array
	* @param  boolean $overwrite True to overwrite the current (non-empty)
	*         cache
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function set($xml_array, $overwrite = false)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->set(+xml_array, +overwrite)- (#echo(__LINE__)#)"); }
		$return = false;

		if ((!isset($this->data) || $overwrite) && is_array($xml_array))
		{
			$this->data = $xml_array;
			$return = true;
		}

		return $return;
	}

/**
	* Sets the EventHandler.
	*
	* @param object $event_handler EventHandler to use
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function setEventHandler($event_handler)
	{
		if ($event_handler !== NULL) { $event_handler->debug("#echo(__FILEPATH__)# -Xml->setEventHandler(+event_handler)- (#echo(__LINE__)#)"); }
		$this->event_handler = $event_handler;
	}

/**
	* Converts XML data into a multi-dimensional or merged array ...
	*
	* @param  string &$data Input XML data
	* @param  boolean $strict_standard Be standard conform
	* @param  boolean $treemode Create a multi-dimensional result
	* @return mixed Multi-dimensional XML tree or merged array; False on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2array(&$data, $treemode = true, $strict_standard = true)
	{
		if ($this->event_handler !== NULL) { $this->event_handler->debug("#echo(__FILEPATH__)# -Xml->xml2array(+data, +treemode, +strict_standard)- (#echo(__LINE__)#)"); }
		$return = false;

		if ($this->data_parser_name == "SimpleXML")
		{
			$parser_object = simplexml_load_string($data);
			if (is_object($parser_object)) { $return = ($treemode ? $this->data_parser->xml2arraySimpleXML($parser_object, $strict_standard) : $this->data_parser->xml2arraySimpleXMLMerged($parser_object)); }
		}

		if ($this->data_parser_name == "XMLReader")
		{
			$parser_object = new /*#ifdef(PHP5n) */\XMLReader/* #*//*#ifndef(PHP5n):XMLReader:#*/();
			$parser_object->XML($data);
			if (is_object($parser_object)) { $return = ($treemode ? $this->data_parser->xml2arrayXMLReader($parser_object, $strict_standard) : $this->data_parser->xml2arrayXMLReaderMerged($parser_object)); }
		}

		if ($this->data_parser_name = "expat")
		{
			$parser_ptr = xml_parser_create();

			if ($parser_ptr)
			{
				xml_parser_set_option($parser_ptr, XML_OPTION_CASE_FOLDING, 0);
				xml_set_object($parser_ptr, $this->data_parser);

				if ($treemode)
				{
					$this->data_parser->defineMode("tree");
					$this->data_parser->defineStrictStandard($strict_standard);

					xml_set_character_data_handler($parser_ptr, "expatCData");
					xml_set_element_handler($parser_ptr, "expatElementStart", "expatElementEnd");
					xml_parse($parser_ptr, $data, true);
					xml_parser_free($parser_ptr);

					$return = $this->data_parser->xml2arrayExpat();
				}
				else
				{
					$this->data_parser->defineMode("merged");

					xml_set_character_data_handler($parser_ptr, "ExpatMergedCData");
					xml_set_element_handler($parser_ptr, "expatMergedElementStart", "expatMergedElementEnd");
					xml_parse($parser_ptr, $data, true);
					xml_parser_free($parser_ptr);

					$return = $this->data_parser->xml2arrayExpatMerged();
				}
			}
		}

		if ($treemode && $this->data_parse_only)
		{
			$this->data = array();
			$this->data_cache_node = "";
			$this->data_cache_ptr = NULL;
			$this->nsUnregister();
		}

		return $return;
	}
}

if (!defined("USE_xml_implementation")) { define("USE_xml_implementation", "autoselect"); }

//j// EOF