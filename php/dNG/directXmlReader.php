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

if (!defined ("CLASS_directXmlReader"))
{
/**
* This class provides a bridge between PHP and XML to read XML on the fly.
*
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    ext_core
* @subpackage xml
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;w3c
*             W3C (R) Software License
*/
class directXmlReader
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
	* @var mixed $data_cache_pointer Reference of the cached node pointer
	*      (string if unset)
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $data_cache_pointer;
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
	* @var array $debug Debug message container
*/
	/*#ifndef(PHP4) */public/* #*//*#ifdef(PHP4):var:#*/ $debug;
/**
	* @var boolean $debugging True if we should fill the debug message
	*      container
*/
	/*#ifndef(PHP4) */protected/* #*//*#ifdef(PHP4):var:#*/ $debugging;

/* -------------------------------------------------------------------------
Construct the class using old and new behavior
------------------------------------------------------------------------- */

/**
	* Constructor (PHP5+) __construct (directXmlReader)
	*
	* @param string $f_charset Charset to be added as information to XML output
	* @param boolean $f_parse_only Parse data only
	* @param integer $f_time Current UNIX timestamp
	* @param integer $f_timeout_count Retries before timing out
	* @param string $f_ext_xml_path Path to the XML parser files.
	* @param boolean $f_debug Debug flag
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __construct ($f_charset = "UTF-8",$f_parse_only = true,$f_time = -1,$f_timeout_count = 5,$f_ext_xml_path = "",$f_debug = false)
	{
		$this->debugging = $f_debug;
		if ($this->debugging) { $this->debug = array ("directXmlReader/#echo(__FILEPATH__)# -Xml->__construct (directXmlReader)- (#echo(__LINE__)#)"); }

/* -------------------------------------------------------------------------
Force or automatically select an implemenation 
------------------------------------------------------------------------- */

		if (strlen ($f_ext_xml_path)) { $f_ext_xml_path .= "/"; }
		$this->data_parser = NULL;

		if ((USE_xml_implementation == "autoselect")||(USE_xml_implementation == "expat"))
		{
			if (!defined ("CLASS_directXmlParserExpat")) { @include_once ($f_ext_xml_path."directXmlParserExpat.php"); }
			if ((function_exists ("xml_parser_create"))&&(defined ("CLASS_directXmlParserExpat"))) { $this->data_parser = new directXmlParserExpat ($this,$f_debug); }
		}

		if ((USE_xml_implementation == "autoselect")&&($this->data_parser == NULL)||(USE_xml_implementation == "SimpleXML"))
		{
			if (!defined ("CLASS_directXmlParserSimpleXML")) { @include_once ($f_ext_xml_path."directXmlParserSimpleXML.php"); }
			if ((function_exists ("simplexml_load_string"))&&(defined ("CLASS_directXmlParserSimpleXML"))) { $this->data_parser = new directXmlParserSimpleXML ($this,$f_time,$f_timeout_count,$f_debug); }
		}

		if ((USE_xml_implementation == "autoselect")&&($this->data_parser == NULL)||(USE_xml_implementation == "XMLReader"))
		{
			if (!defined ("CLASS_directXmlParserXMLReader")) { @include_once ($f_ext_xml_path."directXmlParserXMLReader.php"); }
			if ((class_exists ("XMLReader",/*#ifndef(PHP4) */false/* #*/))&&(defined ("CLASS_directXmlParserXMLReader"))) { $this->data_parser = new directXmlParserXMLReader ($this,$f_time,$f_timeout_count,$f_debug); }
		}

/* -------------------------------------------------------------------------
Initiate the array tree cache
------------------------------------------------------------------------- */

		$this->data = array ();
		$this->data_cache_node = "";
		$this->data_cache_pointer = NULL;
		$this->data_charset = strtoupper ($f_charset);
		$this->data_ns = array ();
		$this->data_ns_compact = array ();
		$this->data_ns_counter = 0;
		$this->data_ns_default = array ();
		$this->data_ns_predefined_compact = array ();
		$this->data_ns_predefined_default = array ();
		$this->data_parse_only = $f_parse_only;
	}
/*#ifdef(PHP4):
/**
	* Constructor (PHP4) directXmlReader
	*
	* @param string $f_charset Charset to be added as information to XML output
	* @param boolean $f_parse_only Parse data only
	* @param integer $f_time Current UNIX timestamp
	* @param integer $f_timeout_count Retries before timing out
	* @param string $f_ext_xml_path Path to the XML parser files.
	* @param boolean $f_debug Debug flag
	* @since v0.1.00
*\/
	function directXmlReader ($f_charset = "UTF-8",$f_parse_only = true,$f_time = -1,$f_timeout_count = 5,$f_ext_xml_path = "",$f_debug = false) { $this->__construct ($f_charset,$f_parse_only,$f_time,$f_timeout_count,$f_ext_xml_path,$f_debug); }
:#\n*/
/**
	* Destructor (PHP5+) __destruct (directXmlReader)
	*
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function __destruct () { $this->data_parser = NULL; }

/**
	* Builds recursively a valid XML ouput reflecting the given XML array tree.
	*
	* @param  array &$f_xml_array XML array tree level to work on
	* @param  boolean $f_strict_standard Be standard conform
	* @return string XML output string
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function array2xml (&$f_xml_array,$f_strict_standard = true)
	{
		if ($this->debugging) { $this->debug[] = "directXmlReader/#echo(__FILEPATH__)# -Xml->array2xml (+f_xml_array,+f_strict_standard)- (#echo(__LINE__)#)"; }
		$f_return = "";

		if ((is_array ($f_xml_array))&&(!empty ($f_xml_array)))
		{
			foreach ($f_xml_array as $f_xml_node_array)
			{
				if (isset ($f_xml_node_array['xml.mtree']))
				{
					unset ($f_xml_node_array['xml.mtree']);
					$f_return .= $this->array2xml ($f_xml_node_array,$f_strict_standard);
				}
				elseif (isset ($f_xml_node_array['xml.item']))
				{
					if ($this->debugging) { $f_return .= "\n"; }
					$f_return .= $this->array2xmlItemEncoder ($f_xml_node_array['xml.item'],false,$f_strict_standard);
					if ($this->debugging) { $f_return .= "\n"; }

					if (preg_match ("#^\d#",$f_xml_node_array['xml.item']['tag'])) { $f_xml_node_tag = "digitstart__".$f_xml_node_array['xml.item']['tag']; }
					else { $f_xml_node_tag = $f_xml_node_array['xml.item']['tag']; }

					unset ($f_xml_node_array['xml.item']);
					$f_return .= $this->array2xml ($f_xml_node_array,$f_strict_standard);

					if ($this->debugging) { $f_return .= "\n"; }
					$f_return .= "</$f_xml_node_tag>";
				}
				elseif (strlen ($f_xml_node_array['tag']))
				{
					if ($this->debugging) { $f_return .= "\n"; }
					$f_return .= $this->array2xmlItemEncoder ($f_xml_node_array,true,$f_strict_standard);
				}
			}
		}

		return trim ($f_return);
	}

/**
	* Builds recursively a valid XML ouput reflecting the given XML array tree.
	*
	* @param  array $f_data Array containing information about the current item
	* @param  boolean $f_close_tag Output will contain a ending tag if true
	* @param  boolean $f_strict_standard Be standard conform
	* @return string XML output string
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function array2xmlItemEncoder ($f_data,$f_close_tag = true,$f_strict_standard = true)
	{
		if ($this->debugging) { $this->debug[] = "directXmlReader/#echo(__FILEPATH__)# -Xml->array2xmlItemEncoder (+f_data,+f_close_tag,+f_strict_standard)- (#echo(__LINE__)#)"; }
		$f_return = "";

		$f_value_attribute_check = ($f_strict_standard ? false : true);

		if (is_array ($f_data))
		{
			if (strlen ($f_data['tag']))
			{
				if (preg_match ("#^\d#",$f_data['tag'])) { $f_data['tag'] = "digitstart__".$f_data['tag']; }
				$f_return .= "<".$f_data['tag'];

				if (isset ($f_data['attributes']))
				{
					foreach ($f_data['attributes'] as $f_key => $f_value)
					{
						if ((!$f_strict_standard)&&($f_key == "value")&&(!strlen ($f_data['value']))) { $f_data['value'] = $f_value; }
						else
						{
							$f_value = str_replace (array ("&","<",">",'"'),(array ("&amp;","&lt;","&gt;","&quot;")),$f_value);
							if ($this->data_charset != "UTF-8") { $f_value = mb_convert_encoding ($f_value,$this->data_charset,"UTF-8"); }

							$f_return .= " $f_key=\"$f_value\"";
						}
					}
				}

				if ((isset ($f_data['value']))&&(($f_strict_standard)||(strlen ($f_data['value']))))
				{
					if ($f_value_attribute_check)
					{
						if (strpos ($f_data['value'],"&") !== false) { $f_value_attribute_check = false; }
						elseif (strpos ($f_data['value'],"<") !== false) { $f_value_attribute_check = false; }
						elseif (strpos ($f_data['value'],">") !== false) { $f_value_attribute_check = false; }
						elseif (strpos ($f_data['value'],'"') !== false) { $f_value_attribute_check = false; }
						elseif (preg_match ("#\s#",(str_replace (" ","_",$f_data['value'])))) { $f_value_attribute_check = false; }
					}

					if ($f_value_attribute_check)
					{
						if ($this->data_charset != "UTF-8") { $f_data['value'] = mb_convert_encoding ($f_data['value'],$this->data_charset,"UTF-8"); }
						$f_return .= " value=\"$f_data[value]\"";
					}
				}

				if (($f_value_attribute_check)&&($f_close_tag)) { $f_return .= " />"; }
				else
				{
					$f_return .= ">";

					if ((isset ($f_data['value']))&&(!$f_value_attribute_check))
					{
						if ($this->data_charset != "UTF-8") { $f_data['value'] = mb_convert_encoding ($f_data['value'],$this->data_charset,"UTF-8"); }

						if ((strpos ($f_data['value'],"<") === false)&&(strpos ($f_data['value'],">") === false))
						{
							$f_data['value'] = str_replace ("&","&amp;",$f_data['value']);
							$f_return .= $f_data['value'];
						}
						else
						{
							if (strpos ($f_data['value'],"]]>") !== false) { $f_data['value'] = str_replace ("]]>","]]]]><![CDATA[>",$f_data['value']); }
							$f_return .= "<![CDATA[{$f_data['value']}]]>";
						}
					}
				}

				if ((!$f_value_attribute_check)&&($f_close_tag)) { $f_return .= "</$f_data[tag]>"; }
			}
		}

		return $f_return;
	}

/**
	* Changes the object behaviour of deleting cached data after parsing is
	* completed.
	*
	* @param  boolean $f_parse_only Parse data only
	* @return boolean Accepted state
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function defineParseOnly ($f_parse_only = true)
	{
		if ($this->debugging) { $this->debug[] = "directXmlReader/#echo(__FILEPATH__)# -Xml->defineParseOnly (+f_parse_only)- (#echo(__LINE__)#)"; }

		if (((is_bool ($f_parse_only))||(is_string ($f_parse_only)))&&($f_parse_only)) { $this->data_parse_only = true; }
		elseif (($f_parse_only === NULL)&&(!$this->data_parse_only)) { $this->data_parse_only = true; }
		else { $this->data_parse_only = false; }

		return $this->data_parse_only;
	}

/**
	* This operation just gives back the content of $this->data.
	*
	* @return mixed XML data on success; false on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function get ()
	{
		if ($this->debugging) { $this->debug[] = "directXmlReader/#echo(__FILEPATH__)# -Xml->get ()- (#echo(__LINE__)#)"; }

		if (isset ($this->data)) { return $this->data; }
		else { return false; }
	}

/**
	* Adds a XML node with content - recursively if required.
	*
	* @param  string $f_node_path Path to the new node - delimiter is space
	* @param  string $f_value Value for the new node
	* @param  array $f_attributes Attributes of the node
	* @param  boolean $f_add_recursively True to create the required tree
	*         recursively
	* @return boolean False on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nodeAdd ($f_node_path,$f_value = "",$f_attributes = "",$f_add_recursively = true)
	{
		if ($this->debugging) { $this->debug[] = "directXmlReader/#echo(__FILEPATH__)# -Xml->nodeAdd ($f_node_path,+f_value,+f_attributes,+f_add_recursively)- (#echo(__LINE__)#)"; }
		$f_return = false;

		if ((is_string ($f_node_path))&&(!is_array ($f_value))&&(!is_object ($f_value)))
		{
			$f_node_path = $this->nsTranslatePath ($f_node_path);

			if ((strlen ($this->data_cache_node))&&(/*#ifndef(PHP4) */stripos ($this->data_cache_node,$f_node_path) === 0/* #*//*#ifdef(PHP4):preg_match ("#^".(preg_quote ($f_node_path,"#"))."#i",$this->data_cache_node):#*/))
			{
				$f_node_path = trim (substr ($f_node_path,(strlen ($this->data_cache_node))));
				$f_node_path_done = $this->data_cache_node;
				$f_node_pointer =& $this->data_cache_pointer;
			}
			else
			{
				$f_node_path_done = "";
				$f_node_pointer =& $this->data;
			}

			$f_nodes_array = explode (" ",$f_node_path);
			$f_continue_check = true;

			while (($f_continue_check)&&(!empty ($f_nodes_array)))
			{
				$f_continue_check = false;
				$f_node_name = array_shift ($f_nodes_array);

				if (preg_match ("#^(.+?)\#(\d+)$#",$f_node_name,$f_result_array))
				{
					$f_node_name = $f_result_array[1];
					$f_node_position = $f_result_array[2];
				}
				else { $f_node_position = -1; }

				if (empty ($f_nodes_array))
				{
					$f_node_array = array ("tag" => $f_node_name,"value" => $f_value,"xmlns" => array ());
					$f_node_ns_check = true;
					$f_node_ns_name = "";
					if (isset ($f_node_pointer['xml.item']['xmlns'])) { $f_node_array['xmlns'] = $f_node_pointer['xml.item']['xmlns']; }

					if ((is_array ($f_attributes))&&(!empty ($f_attributes)))
					{
						if (isset ($f_attributes['xmlns']))
						{
							if (strlen ($f_attributes['xmlns']))
							{
								if (isset ($this->data_ns_default[$f_attributes['xmlns']]))
								{
									$f_node_array['xmlns']['@'] = $this->data_ns_default[$f_attributes['xmlns']];
									$f_node_ns_name = $this->data_ns_default[$f_attributes['xmlns']].":".$f_node_name;
								}
								else
								{
									$this->data_ns_counter++;
									$this->data_ns_default[$f_attributes['xmlns']] = $this->data_ns_counter;
									$this->data_ns_compact[$this->data_ns_counter] = $f_attributes['xmlns'];
									$f_node_array['xmlns']['@'] = $this->data_ns_counter;
									$f_node_ns_name = $this->data_ns_counter.":".$f_node_name;
								}
							}
							elseif (isset ($f_node_array['xmlns']['@'])) { unset ($f_node_array['xmlns']['@']); }
						}

						foreach ($f_attributes as $f_key => $f_value)
						{
							if (/*#ifndef(PHP4) */stripos ($f_key,"xmlns:") === 0/* #*//*#ifdef(PHP4):preg_match ("#^xmlns\:#i",$f_key):#*/)
							{
								$f_ns_name = substr ($f_key,6);

								if (strlen ($f_value)) { $f_node_array['xmlns'][$f_ns_name] = ((isset ($this->data_ns_default[$f_value])) ? $this->data_ns_default[$f_value] : $f_value); }
								elseif (isset ($f_node_array['xmlns'][$f_ns_name])) { unset ($f_node_array['xmlns'][$f_ns_name]); }
							}
						}

						$f_node_array['attributes'] = $f_attributes; 
					}

					if (preg_match ("#^(.+?):(\w+)$#",$f_node_name,$f_result_array))
					{
						if (is_numeric ($f_node_array['xmlns'][$f_result_array[1]])) { $f_node_ns_name = $f_node_array['xmlns'][$f_result_array[1]].":".$f_result_array[2]; }
						else { $f_node_ns_check = false; }
					}
					elseif (isset ($f_node_array['xmlns']['@'])) { $f_node_ns_name = $f_node_array['xmlns']['@'].":".$f_node_name; }
					else { $f_node_ns_check = false; }

					if (strlen ($f_node_path_done))
					{
						if ($f_node_ns_check) { $this->data_ns_predefined_compact[$f_node_path_done." ".$f_node_name] = (isset ($this->data_ns_predefined_compact[$f_node_path_done]) ? $this->data_ns_predefined_compact[$f_node_path_done] : $f_node_path_done)." ".$f_node_ns_name; }
						else { $this->data_ns_predefined_compact[$f_node_path_done." ".$f_node_name] = $this->data_ns_predefined_compact[$f_node_path_done]." ".$f_node_name; }

						$this->data_ns_predefined_default[$this->data_ns_predefined_compact[$f_node_path_done." ".$f_node_name]] = $f_node_path_done." ".$f_node_name;
					}
					elseif ($f_node_ns_check)
					{
						$this->data_ns_predefined_compact[$f_node_name] = $f_node_ns_name;
						$this->data_ns_predefined_default[$f_node_ns_name] = $f_node_name;
					}
					else
					{
						$this->data_ns_predefined_compact[$f_node_name] = $f_node_name;
						$this->data_ns_predefined_default[$f_node_name] = $f_node_name;
					}

					if (isset ($f_node_pointer[$f_node_name]))
					{
						if (isset ($f_node_pointer[$f_node_name]['xml.mtree']))
						{
							$f_node_pointer[$f_node_name]['xml.mtree']++;
							$f_node_pointer[$f_node_name][] = $f_node_array;
						}
						else { $f_node_pointer[$f_node_name] = array ("xml.mtree" => 1,$f_node_pointer[$f_node_name],$f_node_array); }
					}
					else { $f_node_pointer[$f_node_name] = $f_node_array; }

					$f_return = true;
				}
				else
				{
					if (isset ($f_node_pointer[$f_node_name]))
					{
						if (isset ($f_node_pointer[$f_node_name]['xml.mtree']))
						{
							if ($f_node_position >= 0)
							{
								if (isset ($f_node_pointer[$f_node_name][$f_node_position]))
								{
									$f_continue_check = true;
									$f_return = true;

									if (!isset ($f_node_pointer[$f_node_name][$f_node_position]['xml.item'])) { $f_node_pointer[$f_node_name][$f_node_position] = array ("xml.item" => $f_node_pointer[$f_node_name][$f_node_position]); }
									$f_node_pointer =& $f_node_pointer[$f_node_name][$f_node_position];
								}
							}
							elseif (isset ($f_node_pointer[$f_node_name][$f_node_pointer[$f_node_name]['xml.mtree']]))
							{
								$f_continue_check = true;
								$f_return = true;

								if (!isset ($f_node_pointer[$f_node_name][$f_node_pointer[$f_node_name]['xml.mtree']]['xml.item'])) { $f_node_pointer[$f_node_name][$f_node_pointer[$f_node_name]['xml.mtree']] = array ("xml.item" => $f_node_pointer[$f_node_name][$f_node_pointer[$f_node_name]['xml.mtree']]); }
								$f_node_pointer =& $f_node_pointer[$f_node_name][$f_node_pointer[$f_node_name]['xml.mtree']];
							}
						}
						elseif (isset ($f_node_pointer[$f_node_name]['xml.item']))
						{
							$f_continue_check = true;
							$f_node_pointer =& $f_node_pointer[$f_node_name];
						}
						else
						{
							$f_continue_check = true;
							$f_node_pointer[$f_node_name]['level'] = ((isset ($f_node_pointer['xml.item']['level'])) ? (1 + $f_node_pointer['xml.item']['level']) : 1);
							$f_node_pointer[$f_node_name] = array ("xml.item" => $f_node_pointer[$f_node_name]);
							$f_node_pointer =& $f_node_pointer[$f_node_name];
						}
					}

					if ((!$f_continue_check)&&($f_add_recursively))
					{
						$f_continue_check = true;
						$f_node_level = ((isset ($f_node_pointer['xml.item']['level'])) ? (1 + $f_node_pointer['xml.item']['level']) : 1);
						$f_node_array = array ("tag" => $f_node_name,"level" => $f_node_level,"xmlns" => array ());
						if (isset ($f_node_pointer['xml.item']['xmlns'])) { $f_node_array['xmlns'] = $f_node_pointer['xml.item']['xmlns']; }

						$f_node_pointer[$f_node_name] = array ("xml.item" => $f_node_array);
						$f_node_pointer =& $f_node_pointer[$f_node_name];
					}

					if ($f_node_path_done) { $f_node_path_done .= " "; }
					$f_node_path_done .= $f_node_name;
				}
			}
		}

		return $f_return;
	}

/**
	* Registers a namespace (URI) for later use with this XML bridge class.
	*
	* @param string $f_ns Output relevant namespace definition
	* @param string $f_uri Uniform Resource Identifier
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nsRegister ($f_ns,$f_uri)
	{
		if ($this->debugging) { $this->debug[] = "directXmlReader/#echo(__FILEPATH__)# -Xml->nsRegister ($f_ns,$f_uri)- (#echo(__LINE__)#)"; }
		$this->data_ns[$f_ns] = $f_uri;

		if (!isset ($this->data_ns_default[$f_uri]))
		{
			$this->data_ns_counter++;
			$this->data_ns_default[$f_uri] = $this->data_ns_counter;
			$this->data_ns_compact[$this->data_ns_counter] = $f_uri;
		}
	}

/**
	* Translates the tag value if a predefined namespace matches. The translated
	* tag will be saved as "tag_ns" and "tag_parsed".
	*
	* @param  array $f_node XML array node
	* @return array Checked XML array node
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nsTranslate ($f_node)
	{
		if ($this->debugging) { $this->debug[] = "directXmlReader/#echo(__FILEPATH__)# -Xml->nsTranslate (+f_node)- (#echo(__LINE__)#)"; }
		$f_return = $f_node;

		if ((is_array ($f_node))&&(isset ($f_node['xmlns']))&&(is_array ($f_node['xmlns']))&&(isset ($f_node['tag'])))
		{
			$f_return['tag_ns'] = "";
			$f_return['tag_parsed'] = $f_node['tag'];

			if ((preg_match ("#^(.+?):(\w+)$#",$f_node['tag'],$f_result_array))&&(isset ($f_node['xmlns'][$f_result_array[1]]/*#ifndef(PHP4) */,/* #*//*#ifdef(PHP4):) && isset (:#*/$this->data_ns_compact[$f_node['xmlns'][$f_result_array[1]]])))
			{
				$f_tag_ns = array_search ($this->data_ns_compact[$f_node['xmlns'][$f_result_array[1]]],$this->data_ns);

				if ($f_tag_ns)
				{
					$f_return['tag_ns'] = $f_tag_ns;
					$f_return['tag_parsed'] = $f_tag_ns.":".$f_result_array[2];
				}
			}

			if (isset ($f_node['attributes']))
			{
				foreach ($f_node['attributes'] as $f_key => $f_value)
				{
					if ((preg_match ("#^(.+?):(\w+)$#",$f_key,$f_result_array))&&(isset ($f_node['xmlns'][$f_result_array[1]]/*#ifndef(PHP4) */,/* #*//*#ifdef(PHP4):) && isset (:#*/$this->data_ns_compact[$f_node['xmlns'][$f_result_array[1]]])))
					{
						$f_tag_ns = array_search ($this->data_ns_compact[$f_node['xmlns'][$f_result_array[1]]],$this->data_ns);

						if ($f_tag_ns)
						{
							$f_return['attributes'][($f_tag_ns.":".$f_result_array[2])] = $f_value;
							unset ($f_return['attributes'][$f_key]);
						}
					}
				}
			}
		}

		return $f_return;
	}

/**
	* Checks input path for predefined namespaces converts it to the internal
	* path.
	*
	* @param  string $f_node_path Path to the new node - delimiter is space
	* @return string Output node path
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */protected /* #*/function nsTranslatePath ($f_node_path)
	{
		if ($this->debugging) { $this->debug[] = "directXmlReader/#echo(__FILEPATH__)# -Xml->nsTranslatePath ($f_node_path)- (#echo(__LINE__)#)"; }

		$f_nodes_array = explode (" ",$f_node_path);
		$f_return = $f_node_path;
		$f_node_path = "";

		while (!empty ($f_nodes_array))
		{
			$f_node_name = array_shift ($f_nodes_array);
			if ($f_node_path) { $f_node_path .= " "; }

			if (strpos ($f_node_name,":") === false) { $f_node_path .= $f_node_name; }
			else
			{
				if (preg_match ("#^(.+?):(\w+)$#",$f_node_name,$f_result_array))
				{
					if (isset ($this->data_ns[$f_result_array[1]])) { $f_node_path .= (isset ($this->data_ns_default[$this->data_ns[$f_result_array[1]]]) ? $this->data_ns_default[$this->data_ns[$f_result_array[1]]] : $f_result_array[1]).":".$f_result_array[2]; }
					else { $f_node_path .= $f_result_array[1].":".$f_result_array[2]; }
				}
				else { $f_node_path .= $f_node_name; }
			}
		}

		if (isset ($this->data_ns_predefined_default[$f_node_path])) { $f_return = $this->data_ns_predefined_default[$f_node_path]; }
		return $f_return;
	}

/**
	* Unregisters a namespace or clears the cache (if $f_ns is empty).
	*
	* @param string $f_ns Output relevant namespace definition
	* @since v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function nsUnregister ($f_ns = "")
	{
		if ($this->debugging) { $this->debug[] = "directXmlReader/#echo(__FILEPATH__)# -Xml->nsUnregister ($f_ns)- (#echo(__LINE__)#)"; }

		if (strlen ($f_ns))
		{
			if (isset ($this->data_ns[$f_ns]))
			{
				unset ($this->data_ns_compact[$this->data_ns_default[$this->data_ns[$f_ns]]]);
				unset ($this->data_ns_default[$this->data_ns[$f_ns]]);
				unset ($this->data_ns[$f_ns]);
			}
		}
		else
		{
			$this->data_ns = array ();
			$this->data_ns_compact = array ();
			$this->data_ns_counter = 0;
			$this->data_ns_default = array ();
			$this->data_ns_predefined_compact = array ();
			$this->data_ns_predefined_default = array ();
		}
	}

/**
	* "Imports" a XML tree into the cache.
	*
	* @param  array $f_xml_array Input array
	* @param  boolean $f_overwrite True to overwrite the current (non-empty)
	*         cache
	* @return boolean True on success
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function set ($f_xml_array,$f_overwrite = false)
	{
		if ($this->debugging) { $this->debug[] = "directXmlReader/#echo(__FILEPATH__)# -Xml->set (+f_xml_array,+f_overwrite)- (#echo(__LINE__)#)"; }
		$f_return = false;

		if (((!isset ($this->data))||($f_overwrite))&&(is_array ($f_xml_array)))
		{
			$this->data = $f_xml_array;
			$f_return = true;
		}

		return $f_return;
	}

/**
	* Converts XML data into a multi-dimensional or merged array ...
	*
	* @param  string &$f_data Input XML data
	* @param  boolean $f_strict_standard Be standard conform
	* @param  boolean $f_treemode Create a multi-dimensional result
	* @return mixed Multi-dimensional XML tree or merged array; False on error
	* @since  v0.1.00
*/
	/*#ifndef(PHP4) */public /* #*/function xml2array (&$f_data,$f_treemode = true,$f_strict_standard = true)
	{
		if ($this->debugging) { $this->debug[] = "directXmlReader/#echo(__FILEPATH__)# -Xml->xml2array (+f_data,+f_treemode,+f_strict_standard)- (#echo(__LINE__)#)"; }
		$f_return = false;

		if (defined ("CLASS_directXmlParserSimpleXML"))
		{
			$f_parser_object = simplexml_load_string ($f_data);
			if (is_object ($f_parser_object)) { $f_return = ($f_treemode ? $this->data_parser->xml2arraySimpleXML ($f_parser_object,$f_strict_standard) : $this->data_parser->xml2arraySimpleXMLMerged ($f_parser_object)); }
		}

		if (defined ("CLASS_directXmlParserXMLReader"))
		{
			$f_parser_object = new XMLReader ();
			$f_parser_object->XML ($f_data);
			if (is_object ($f_parser_object)) { $f_return = ($f_treemode ? $this->data_parser->xml2arrayXMLReader ($f_parser_object,$f_strict_standard) : $this->data_parser->xml2arrayXMLReaderMerged ($f_parser_object)); }
		}

		if (defined ("CLASS_directXmlParserExpat"))
		{
			$f_parser_pointer = xml_parser_create ();

			if ($f_parser_pointer)
			{
				xml_parser_set_option ($f_parser_pointer,XML_OPTION_CASE_FOLDING,0);
				xml_set_object ($f_parser_pointer,$this->data_parser);

				if ($f_treemode)
				{
					$this->data_parser->defineMode ("tree");
					$this->data_parser->defineStrictStandard ($f_strict_standard);

					xml_set_character_data_handler ($f_parser_pointer,"expatCData");
					xml_set_element_handler ($f_parser_pointer,"expatElementStart","expatElementEnd");
					xml_parse ($f_parser_pointer,$f_data,true);
					xml_parser_free ($f_parser_pointer);

					$f_return = $this->data_parser->xml2arrayExpat ();
				}
				else
				{
					$this->data_parser->defineMode ("merged");

					xml_set_character_data_handler ($f_parser_pointer,"ExpatMergedCData");
					xml_set_element_handler ($f_parser_pointer,"expatMergedElementStart","expatMergedElementEnd");
					xml_parse ($f_parser_pointer,$f_data,true);
					xml_parser_free ($f_parser_pointer);

					$f_return = $this->data_parser->xml2arrayExpatMerged ();
				}
			}
		}

		if (($f_treemode)&&($this->data_parse_only))
		{
			$this->data = array ();
			$this->data_cache_node = "";
			$this->data_cache_pointer = NULL;
			$this->nsUnregister ();
		}

		return $f_return;
	}
}

/* -------------------------------------------------------------------------
Mark this class as the most up-to-date one
------------------------------------------------------------------------- */

define ("CLASS_directXmlReader",true);

if (!defined ("USE_xml_implementation")) { define ("USE_xml_implementation","autoselect"); }
}

//j// EOF
?>