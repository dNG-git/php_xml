# -*- coding: utf-8 -*-
##j## BOF

"""
XML (Extensible Markup Language) is the easiest way to use a descriptive
language for controlling applications locally and world wide.

@internal   We are using epydoc (JavaDoc style) to automate the
            documentation process for creating the Developer's Manual.
            Use the following line to ensure 76 character sizes:
----------------------------------------------------------------------------
@author     direct Netware Group
@copyright  (C) direct Netware Group - All rights reserved
@package    ext_core
@subpackage xml
@since      v0.1.00
@license    http://www.direct-netware.de/redirect.php?licenses;mpl2
            Mozilla Public License, v. 2.0
"""
"""n// NOTE
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
NOTE_END //n"""

import re

try:
#
	import java.lang.System
	_direct_xml_reader_mode = "java"
#
except ImportError: _direct_xml_reader_mode = None

try:
#
	import clr
	clr.AddReference ("System.Xml")
	from System.IO import StringReader
	from System.Xml import XmlDocument,XmlNodeReader

	from .xml_parser_MonoXML import direct_xml_parser_MonoXML
	_direct_xml_reader_mode = "mono"
#
except ImportError: pass

if (_direct_xml_reader_mode == None):
#
	from xml.parsers import expat

	from .xml_parser_expat import direct_xml_parser_expat
	_direct_xml_reader_mode = "py"
#

try: _unicode_object = { "type": unicode,"str": unicode.encode }
except: _unicode_object = { "type": bytes,"str": bytes.decode }

class direct_xml_reader (object):
#
	"""
This class provides a bridge between Python and XML to read XML on the fly.

@author     direct Netware Group
@copyright  (C) direct Netware Group - All rights reserved
@package    ext_core
@subpackage xml
@since      v1.0.0
@license    http://www.direct-netware.de/redirect.php?licenses;mpl2
            Mozilla Public License, v. 2.0
	"""

	data = None
	"""
XML data
	"""
	data_cache_node = ""
	"""
Path of the cached node pointer
	"""
	data_cache_pointer = ""
	"""
Reference of the cached node pointer (string if unset)
	"""
	data_charset = ""
	"""
Charset used
	"""
	data_parse_only = True
	"""
Parse data only
	"""
	data_parser = None
	"""
The selected parser implementation
	"""
	data_ns = { }
	"""
Cache for known XML NS (URI)
	"""
	data_ns_compact = { }
	"""
Cache for the compact number of a XML NS
	"""
	data_ns_counter = 0
	"""
Counter for the compact link numbering
	"""
	data_ns_default = { }
	"""
Cache for the XML NS and the corresponding number
	"""
	data_ns_predefined_compact = { }
	"""
Cache of node pathes with a predefined NS (key = Compact name)
	"""
	data_ns_predefined_default = { }
	"""
Cache of node pathes with a predefined NS (key = Full name)
	"""
	debug = None
	"""
Debug message container
	"""

	"""
----------------------------------------------------------------------------
Construct the class
----------------------------------------------------------------------------
	"""

	def __init__ (self,xml_charset = "UTF-8",parse_only = True,current_time = -1,timeout_count = 5,debug = False):
	#
		"""
Constructor __init__ (direct_xml_reader)

@param xml_charset Charset to be added as information to XML output
@param parse_only Parse data only
@param current_time Current UNIX timestamp
@param timeout_count Retries before timing out
@param debug Debug flag
@since v0.1.00
		"""

		global _direct_xml_reader_mode

		if (debug): self.debug = [ "xml/#echo(__FILEPATH__)# -xml_reader.__init__ (direct_xml_reader)- (#echo(__LINE__)#)" ]
		else: self.debug = None

		if (_direct_xml_reader_mode == "mono"): self.data_parser = direct_xml_parser_MonoXML (self,current_time,timeout_count,debug)
		else: self.data_parser = direct_xml_parser_expat (self,debug)

		"""
----------------------------------------------------------------------------
Initiate the array tree cache
----------------------------------------------------------------------------
		"""

		self.data = None
		self.data_cache_node = ""
		self.data_cache_pointer = ""
		self.data_charset = xml_charset.upper ()
		self.data_ns = { }
		self.data_ns_compact = { }
		self.data_ns_counter = 0
		self.data_ns_default = { }
		self.data_ns_predefined_compact = { }
		self.data_ns_predefined_default = { }
		self.data_parse_only = parse_only
	#

	def __del__ (self):
	#
		"""
Destructor __del__ (direct_xml_reader)

@since v0.1.00
		"""

		self.del_direct_xml_reader ()
	#

	def del_direct_xml_reader (self):
	#
		"""
Destructor del_direct_xml_reader (direct_xml_reader)

@since v0.1.00
		"""

		self.data_parser = None
	#

	def array2xml (self,xml_dict,strict_standard = True):
	#
		"""
Builds recursively a valid XML ouput reflecting the given XML array tree.

@param  xml_dict XML array tree level to work on
@param  strict_standard Be standard conform
@return (str) XML output string
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader.array2xml (xml_dict,strict_standard)- (#echo(__LINE__)#)")
		f_return = ""

		if ((type (xml_dict) == dict) and (len (xml_dict) > 0)):
		#
			f_re_tag_digit = re.compile ("\\d")

			for f_xml_node in xml_dict:
			#
				f_xml_node_dict = xml_dict[f_xml_node]

				if ("xml.mtree" in f_xml_node_dict):
				#
					del (f_xml_node_dict['xml.mtree'])
					f_return += self.array2xml (f_xml_node_dict,strict_standard)
				#
				elif ("xml.item" in f_xml_node_dict):
				#
					if (self.debug != None): f_return += "\n"
					f_return += self.array2xml_item_encoder (f_xml_node_dict['xml.item'],False,strict_standard)
					if (self.debug != None): f_return += "\n"

					if (f_re_tag_digit.match (f_xml_node_dict['xml.item']['tag']) == None): f_xml_node_tag = f_xml_node_dict['xml.item']['tag']
					else: f_xml_node_tag = "digitstart__{0}".format (f_xml_node_dict['xml.item']['tag'])

					del (f_xml_node_dict['xml.item'])
					f_return += self.array2xml (f_xml_node_dict,strict_standard)

					if (self.debug != None): f_return += "\n"
					f_return += "</{0}>".format (f_xml_node_tag)
				#
				elif (len (f_xml_node_dict['tag']) > 0):
				#
					if (self.debug != None): f_return += "\n"
					f_return += self.array2xml_item_encoder (f_xml_node_dict,True,strict_standard)
				#
			#
		#

		return f_return.strip ()
	#

	def array2xml_item_encoder (self,data,close_tag = True,strict_standard = True):
	#
		"""
Builds recursively a valid XML ouput reflecting the given XML array tree.

@param  data Array containing information about the current item
@param  close_tag Output will contain a ending tag if true
@param  strict_standard Be standard conform
@return (str) XML output string
@since  v0.1.00
		"""

		global _unicode_object
		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader.array2xml_item_encoder (data,close_tag,strict_standard)- (#echo(__LINE__)#)")

		f_return = ""

		if (strict_standard): f_value_attribute_check = False
		else: f_value_attribute_check = True

		if (type (data) == dict):
		#
			if (len (data['tag']) > 0):
			#
				if (re.compile("\\d").match (data['tag']) != None): data['tag'] = "digitstart__{0}".format (data['tag'])
				f_return += "<{0}".format (data['tag'])

				if ("attributes" in data):
				#
					for f_key in data['attributes']:
					#
						f_type_value = type (data['attributes'][f_key])

						if ((f_type_value == int) or (f_type_value == float)): f_value = str (data['attributes'][f_key])
						else: f_value = data['attributes'][f_key]

						if ((not strict_standard) and (f_key == "value") and (not len (data['value']))): data['value'] = f_value
						else:
						#
							if (type (f_value) == _unicode_object['type']): f_value = _unicode_object['str'] (f_value,"utf-8")
							f_value = f_value.replace ("&","&amp;")
							f_value = f_value.replace ("<","&lt;")
							f_value = f_value.replace (">","&gt;")
							f_value = f_value.replace ('"',"&quot;")
							if (self.data_charset != "UTF-8"): f_value = f_value.encode (self.data_charset)

							f_return += " {0}=\"{1}\"".format (f_key,f_value)
						#
					#
				#

				if (("value" in data) and ((strict_standard) or (len (data['value'])> 0))):
				#
					f_type_data_value = type (data['value'])

					if ((f_type_data_value == int) or (f_type_data_value == float)): data['value'] = str (data['value'])
					else:
					#
						if (f_type_data_value == _unicode_object['type']): data['value'] = _unicode_object['str'] (data['value'],"utf-8")

						if (f_value_attribute_check):
						#
							if (data['value'].find ("&") != -1): f_value_attribute_check = False
							elif (data['value'].find ("<") != -1): f_value_attribute_check = False
							elif (data['value'].find (">") != -1): f_value_attribute_check = False
							elif (data['value'].find ('"') != -1): f_value_attribute_check = False
							elif (re.compile("\\s").search (data['value'].replace (" ","_")) != None): f_value_attribute_check = False
						#
					#

					if (f_value_attribute_check):
					#
						if (self.data_charset != "UTF-8"): data['value'] = data['value'].encode (self.data_charset)
						f_return += " value=\"{0}\"".format (data['value'])
					#
				#

				if ((f_value_attribute_check) and (close_tag)): f_return += " />"
				else:
				#
					f_return += ">"

					if (("value" in data) and (not f_value_attribute_check)):
					#
						if (type (data['value']) == _unicode_object['type']): data['value'] = _unicode_object['str'] (data['value'],"utf-8")
						if (self.data_charset != "UTF-8"): data['value'] = data['value'].encode (self.data_charset)

						if ((data['value'].find ("<") < 0) and (data['value'].find (">") < 0)):
						#
							data['value'] = data['value'].replace ("&","&amp;")
							f_return += data['value']
						#
						else:
						#
							if (data['value'].find ("]]>") != -1): data['value'] = data['value'].replace ("]]>","]]]]><![CDATA[>")
							f_return += "<![CDATA[{0}]]>".format (data['value'])
						#
					#
				#

				if ((not f_value_attribute_check) and (close_tag)): f_return += "</{0}>".format (data['tag'])
			#
		#

		return f_return
	#

	def define_parse_only (self,parse_only = True):
	#
		"""
Changes the object behaviour of deleting cached data after parsing is
completed.

@param  parse_only Parse data only
@return (bool) Accepted state
@since  v0.1.00
		"""

		global _unicode_object
		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader.define_parse_only (parse_only)- (#echo(__LINE__)#)")

		if (type (parse_only) == _unicode_object['type']): parse_only = _unicode_object['str'] (parse_only,"utf-8")
		f_type = type (parse_only)

		if (((f_type == bool) or (f_type == str)) and (parse_only)): self.data_parse_only = True
		elif ((parse_only == None) and (not self.data_parse_only)): self.data_parse_only = True
		else: self.data_parse_only = False

		return self.data_parse_only
	#

	def dict_search (self,needle,haystack):
	#
		"""
Searches haystack for needle. 

@param  needle Value to be searched for
@param  haystack Dict to search in
@return (mixed) Key on success; False on error
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader.dict_search ({0},haystack)- (#echo(__LINE__)#)".format (needle))
		f_return = False

		if (needle in haystack):
		#
			for f_key in haystack:
			#
				if (haystack[f_key] == needle):
				#
					f_return = f_key
					break
				#
			#
		#

		return f_return
	#

	def get (self):
	#
		"""
This operation just gives back the content of self.data.

@return (mixed) XML data on success; false on error
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader.get ()- (#echo(__LINE__)#)")

		if (self.data == None): return False
		else: return self.data
	#

	def node_add (self,node_path,value = "",attributes = "",add_recursively = True):
	#
		"""
Adds a XML node with content - recursively if required.

@param  node_path Path to the new node - delimiter is space
@param  value Value for the new node
@param  attributes Attributes of the node
@param  add_recursively True to create the required tree recursively
@return (bool) False on error
@since  v0.1.00
		"""

		global _unicode_object
		if (type (node_path) == _unicode_object['type']): node_path = _unicode_object['str'] (node_path,"utf-8")

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader.node_add ({0},value,attributes,add_recursively)- (#echo(__LINE__)#)".format (node_path))
		f_return = False

		f_type_value = type (value)

		if ((type (node_path) == str) and (f_type_value != list) and (f_type_value != dict)):
		#
			f_node_path = self.ns_translate_path (node_path)

			if ((len (self.data_cache_node) == 0) or (re.compile("^{0}".format (re.escape (f_node_path)),re.I).match (self.data_cache_node) == None)):
			#
				f_node_path_done = ""
				f_node_pointer = self.data
			#
			else:
			#
				f_node_path = f_node_path[len (self.data_cache_node):].strip ()
				f_node_path_done = self.data_cache_node
				f_node_pointer = self.data_cache_pointer
			#

			f_nodes_list = f_node_path.split (" ")
			f_continue_check = True
			f_re_attributes_xmlns = re.compile ("xmlns\\:",re.I)
			f_re_node_name_xmlns = re.compile ("^(.+?):(\\w+)$")
			f_re_node_position = re.compile ("^(.+?)\\#(\\d+)$")

			while ((f_continue_check) and (len (f_nodes_list) > 0)):
			#
				f_continue_check = False
				f_node_name = f_nodes_list.pop (0)
				f_result_object = f_re_node_position.search (f_node_name)

				if (f_result_object == None): f_node_position = -1
				else:
				#
					f_node_name = f_result_object.group (1)
					f_node_position = f_result_object.group (2)
				#

				if (len (f_nodes_list) > 0):
				#
					if (f_node_name in f_node_pointer):
					#
						if ("xml.mtree" in f_node_pointer[f_node_name]):
						#
							if (f_node_position >= 0):
							#
								if (f_node_position in f_node_pointer[f_node_name]):
								#
									f_continue_check = True
									f_return = True

									if ((type (f_node_pointer[f_node_name][f_node_position]) != dict) or ("xml.item" not in f_node_pointer[f_node_name][f_node_position])): f_node_pointer[f_node_name][f_node_position] = { "xml.item": f_node_pointer[f_node_name][f_node_position] }
									f_node_pointer = f_node_pointer[f_node_name][f_node_position]
								#
							#
							elif (f_node_pointer[f_node_name]['xml.mtree'] in f_node_pointer[f_node_name]):
							#
								f_continue_check = True
								f_node_position = f_node_pointer[f_node_name]['xml.mtree']
								f_return = True

								if ((type (f_node_pointer[f_node_name][f_node_position]) != dict) or ("xml.item" not in f_node_pointer[f_node_name][f_node_position])): f_node_pointer[f_node_name][f_node_position] = { "xml.item": f_node_pointer[f_node_name][f_node_position] }
								f_node_pointer = f_node_pointer[f_node_name][f_node_position]
							#
						#
						elif ("xml.item" in f_node_pointer[f_node_name]):
						#
							f_continue_check = True
							f_node_pointer = f_node_pointer[f_node_name]
						#
						else:
						#
							f_continue_check = True

							if (("xml.item" in f_node_pointer) and ("level" in f_node_pointer['xml.item'])): f_node_pointer[f_node_name]['level'] = (1 + f_node_pointer['xml.item']['level'])
							else: f_node_pointer[f_node_name]['level'] = 1

							f_node_pointer[f_node_name] = { "xml.item": f_node_pointer[f_node_name] }
							f_node_pointer = f_node_pointer[f_node_name]
						#
					#

					if ((not f_continue_check) and (add_recursively)):
					#
						if ("level" in f_node_pointer['xml.item']): f_node_level = (1 + f_node_pointer['xml.item']['level'])
						else: f_node_level = 1

						f_continue_check = True
						f_node_dict = { "tag": f_node_name,"level": f_node_level,"xmlns": { } }
						if ("xmlns" in f_node_pointer['xml.item']): f_node_dict['xmlns'] = f_node_pointer['xml.item']['xmlns']

						f_node_pointer[f_node_name] = { "xml.item": f_node_dict }
						f_node_pointer = f_node_pointer[f_node_name]
					#

					if (len (f_node_path_done) > 0): f_node_path_done += " "
					f_node_path_done += f_node_name
				#
				else:
				#
					f_node_dict = { "tag": f_node_name,"value": value,"xmlns": { } }
					f_node_ns_check = True
					f_node_ns_name = ""
					if (("xml.item" in f_node_pointer) and ("xmlns" in f_node_pointer['xml.item'])): f_node_dict['xmlns'] = f_node_pointer['xml.item']['xmlns']

					if ((type (attributes) == dict) and (len (attributes) > 0)):
					#
						if ("xmlns" in attributes):
						#
							if (len (attributes['xmlns']) > 0):
							#
								if (attributes['xmlns'] in self.data_ns_default):
								#
									f_node_dict['xmlns']['@'] = self.data_ns_default[attributes['xmlns']]
									f_node_ns_name = "{0}:{1}".format (self.data_ns_default[attributes['xmlns']],f_node_name)
								#
								else:
								#
									self.data_ns_counter += 1
									self.data_ns_default[attributes['xmlns']] = self.data_ns_counter
									self.data_ns_compact[self.data_ns_counter] = attributes['xmlns']
									f_node_dict['xmlns']['@'] = self.data_ns_counter
									f_node_ns_name = "{0}:{1}".format (self.data_ns_counter,f_node_name)
								#
							#
							elif ("@" in f_node_dict['xmlns']): del (f_node_dict['xmlns']['@'])
						#

						for f_key in attributes:
						#
							f_value = attributes[f_key]

							if (f_re_attributes_xmlns.match (f_key)):
							#
								f_ns_name = f_key[6:]

								if (len (f_value) > 0):
								#
									if (f_value in self.data_ns_default): f_node_dict['xmlns'][f_ns_name] = self.data_ns_default[f_value]
									else: f_node_dict['xmlns'][f_ns_name] = f_value
								#
								elif (f_ns_name in f_node_dict['xmlns']): del (f_node_dict['xmlns'][f_ns_name])
							#
						#

						f_node_dict['attributes'] = attributes
					#

					f_result_object = f_re_node_name_xmlns.search (f_node_name)

					if (f_result_object != None):
					#
						if ((f_result_object.group (1) in f_node_dict['xmlns']) and (type (f_node_dict['xmlns'][f_result_object.group (1)]) == int)): f_node_ns_name = "{0}:{1}".format (f_node_dict['xmlns'][f_result_object.group (1)],(f_result_object.group (2)))
						else: f_node_ns_check = False
					#
					elif ("@" in f_node_dict['xmlns']): f_node_ns_name = "{0}:{1}".format (f_node_dict['xmlns']['@'],f_node_name)
					else: f_node_ns_check = False

					if (len (f_node_path_done) > 0):
					#
						if (f_node_ns_check): self.data_ns_predefined_compact[("{0} {1}".format (f_node_path_done,f_node_name))] = "{0} {1}".format (self.data_ns_predefined_compact[f_node_path_done],f_node_ns_name)
						else: self.data_ns_predefined_compact[("{0} {1}".format (f_node_path_done,f_node_name))] = "{0} {1}".format (self.data_ns_predefined_compact[f_node_path_done],f_node_name)

						self.data_ns_predefined_default[self.data_ns_predefined_compact[("{0} {1}".format (f_node_path_done,f_node_name))]] = "{0} {1}".format (f_node_path_done,f_node_name)
					#
					elif (f_node_ns_check):
					#
						self.data_ns_predefined_compact[f_node_name] = f_node_ns_name
						self.data_ns_predefined_default[f_node_ns_name] = f_node_name
					#
					else:
					#
						self.data_ns_predefined_compact[f_node_name] = f_node_name
						self.data_ns_predefined_default[f_node_name] = f_node_name
					#

					if (f_node_name in f_node_pointer):
					#
						if ((type (f_node_pointer[f_node_name]) != dict) or ("xml.mtree" not in f_node_pointer[f_node_name])): f_node_pointer[f_node_name] = { "xml.mtree": 1,0: f_node_pointer[f_node_name],1: f_node_dict }
						else:
						#
							f_node_pointer[f_node_name]['xml.mtree'] += 1
							f_node_pointer[f_node_name][f_node_pointer[f_node_name]['xml.mtree']] = f_node_dict
						#
					#
					else: f_node_pointer[f_node_name] = f_node_dict

					f_return = True
				#
			#
		#

		return f_return
	#

	def ns_register (self,ns,uri):
	#
		"""
Registers a namespace (URI) for later use with this XML bridge class.

@param ns Output relevant namespace definition
@param uri Uniform Resource Identifier
@since v0.1.00
		"""

		global _unicode_object
		if (type (ns) == _unicode_object['type']): ns = _unicode_object['str'] (ns,"utf-8")
		if (type (uri) == _unicode_object['type']): uri = _unicode_object['str'] (uri,"utf-8")

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader.ns_register ({0},{1})- (#echo(__LINE__)#)".format (ns,uri))
		self.data_ns[ns] = uri

		if (uri not in self.data_ns_default):
		#
			self.data_ns_counter += 1
			self.data_ns_default[uri] = self.data_ns_counter
			self.data_ns_compact[self.data_ns_counter] = uri
		#
	#

	def ns_translate (self,node):
	#
		"""
Translates the tag value if a predefined namespace matches. The translated
tag will be saved as "tag_ns" and "tag_parsed".

@param  node XML array node
@return (dict) Checked XML array node
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader.ns_translate (node)- (#echo(__LINE__)#)")
		f_return = node

		if ((type (node) == dict) and ("tag" in node) and ("xmlns" in node) and (type (node['xmlns']) == dict)):
		#
			f_return['tag_ns'] = ""
			f_return['tag_parsed'] = node['tag']

			f_re_node_name_xmlns = re.compile ("^(.+?):(\\w+)$")
			f_result_object = f_re_node_name_xmlns.search (node['tag'])

			if ((f_result_object != None) and (f_result_object.group (1) in node['xmlns']) and (node['xmlns'][f_result_object.group (1)] in self.data_ns_compact)):
			#
				f_tag_ns = self.dict_search (self.data_ns_compact[node['xmlns'][f_result_object.group (1)]],self.data_ns)

				if (type (f_tag_ns) != bool):
				#
					f_return['tag_ns'] = f_tag_ns
					f_return['tag_parsed'] = "{0}:{1}".format (f_tag_ns,(f_result_object.group (2)))
				#
			#

			if ("attributes" in data):
			#
				for f_key in data['attributes']:
				#
					f_result_object = f_re_node_name_xmlns.search (f_key)

					if ((f_result_object != None) and (f_result_object.group (1) in node['xmlns']) and (node['xmlns'][f_result_object.group (1)] in self.data_ns_compact)):
					#
						f_tag_ns = self.dict_search (self.data_ns_compact[node['xmlns'][f_result_object.group (1)]],self.data_ns)

						if (type (f_tag_ns) != bool):
						#
							f_return['attributes'][("{0}:{1}".format (f_tag_ns,(f_result_object.group (2))))] = data['attributes'][f_key]
							del (f_return['attributes'][f_key])
						#
					#
				#
			#
		#

		return f_return
	#

	def ns_translate_path (self,node_path):
	#
		"""
Checks input path for predefined namespaces converts it to the internal
path.

@param  node_path Path to the new node - delimiter is space
@return (str) Output node path
@since  v0.1.00
		"""

		global _unicode_object
		if (type (node_path) == _unicode_object['type']): node_path = _unicode_object['str'] (node_path,"utf-8")

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader.ns_translate_path ({0})- (#echo(__LINE__)#)".format (node_path))
		f_return = node_path

		f_nodes_list = node_path.split (" ")
		f_node_path = ""
		f_re_node_name_xmlns = re.compile ("^(.+?):(\\w+)$")

		while (len (f_nodes_list) > 0):
		#
			f_node_name = f_nodes_list.pop (0)
			if (len (f_node_path) > 0): f_node_path += " "

			if (f_node_name.find (":") < 0): f_node_path += f_node_name
			else:
			#
				f_result_object = f_re_node_name_xmlns.search (f_node_name)

				if (f_result_object == None): f_node_path += f_node_name
				else:
				#
					if (f_result_object.group (1) in self.data_ns):
					#
						if (self.data_ns[f_result_object.group (1)] in self.data_ns_default): f_node_path += "{0}:{1}".format (self.data_ns_default[self.data_ns[f_result_object.group (1)]],(f_result_object.group (2)))
						else: f_node_path += "{0}:{1}".format (f_result_object.group (1),(f_result_object.group (2)))
					#
					else: f_node_path += "{0}:{1}".format (f_result_object.group (1),(f_result_object.group (2)))
				#
			#
		#

		if (f_node_path in self.data_ns_predefined_default): f_return = self.data_ns_predefined_default[f_node_path]
		return f_return
	#

	def ns_unregister (self,ns = ""):
	#
		"""
Unregisters a namespace or clears the cache (if $ns is empty).

@param ns Output relevant namespace definition
@since v0.1.00
		"""

		global _unicode_object
		if (type (ns) == _unicode_object['type']): ns = _unicode_object['str'] (ns,"utf-8")

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader.ns_unregister ({0})- (#echo(__LINE__)#)".format (ns))

		if (len (ns) > 0):
		#
			if (ns in self.data_ns):
			#
				del (self.data_ns_compact[self.data_ns_default[self.data_ns[ns]]]);
				del (self.data_ns_default[self.data_ns[ns]]);
				del (self.data_ns[ns]);
			#
		#
		else:
		#
			self.data_ns = { }
			self.data_ns_compact = { }
			self.data_ns_counter = 0
			self.data_ns_default = { }
			self.data_ns_predefined_compact = { }
			self.data_ns_predefined_default = { }
		#
	#

	def set (self,xml_dict,overwrite = False):
	#
		"""
"Imports" a XML tree into the cache.

@param  xml_dict Input array
@param  overwrite True to overwrite the current (non-empty) cache
@return (bool) True on success
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader.set (xml_dict,overwrite)- (#echo(__LINE__)#)")
		f_return = False

		if (((self.data == None) or (overwrite)) and (type (xml_dict) == dict)):
		#
			self.data = xml_dict
			f_return = True
		#

		return f_return
	#

	def xml2array (self,data,treemode = True,strict_standard = True):
	#
		"""
Converts XML data into a multi-dimensional or merged array ...

@param  data Input XML data
@param  strict_standard Be standard conform
@param  treemode Create a multi-dimensional result
@return (mixed) Multi-dimensional XML tree or merged dictionary; False on
        error
@since  v0.1.00
		"""

		global _direct_xml_reader_mode,_unicode_object
		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader.xml2array (data,treemode,strict_standard)- (#echo(__LINE__)#)")
		f_return = False

		try:
		#
			if (_direct_xml_reader_mode == "mono"):
			#
				if (type (data) == _unicode_object['type']): data = _unicode_object['str'] (data,"utf-8")

				f_parser_pointer = XmlDocument ()
				f_parser_pointer.LoadXml (data)
				f_parser_pointer = XmlNodeReader (f_parser_pointer)

				if (f_parser_pointer != None):
				#
					if (treemode): f_return = self.data_parser.xml2array_MonoXML (f_parser_pointer,strict_standard)
					else: f_return = self.data_parser.xml2array_MonoXML_merged (f_parser_pointer)
				#
			#
			elif (re.compile("<\\?xml(.+?)encoding=").search (data) == None):
			#
				f_parser_pointer = expat.ParserCreate ("UTF-8")
				if (type (data) == _unicode_object['type']): data = _unicode_object['str'] (data,"utf-8")
			#
			else: f_parser_pointer = expat.ParserCreate ()
		#
		except: f_parser_pointer = None

		if ((_direct_xml_reader_mode == "py") and (f_parser_pointer != None)):
		#
			if (treemode):
			#
				self.data_parser.define_mode ("tree")
				self.data_parser.define_strict_standard (strict_standard)

				f_parser_pointer.CharacterDataHandler = self.data_parser.expat_cdata
				f_parser_pointer.StartElementHandler = self.data_parser.expat_element_start
				f_parser_pointer.EndElementHandler = self.data_parser.expat_element_end
				f_parser_pointer.Parse (data,True)

				f_return = self.data_parser.xml2array_expat ()
			#
			else:
			#
				self.data_parser.define_mode ("merged")

				f_parser_pointer.CharacterDataHandler = self.data_parser.expat_merged_cdata
				f_parser_pointer.StartElementHandler = self.data_parser.expat_merged_element_start
				f_parser_pointer.EndElementHandler = self.data_parser.expat_merged_element_end
				f_parser_pointer.Parse (data,True)

				f_return = self.data_parser.xml2array_expat_merged ()
			#
		#

		if ((treemode) and (self.data_parse_only)):
		#
			self.data = None
			self.ns_unregister ()
		#

		return f_return
	#
#

##j## EOF