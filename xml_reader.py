# -*- coding: utf-8 -*-
##j## BOF

"""n// NOTE
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
NOTE_END //n"""
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
@license    http://www.direct-netware.de/redirect.php?licenses;w3c
            W3C (R) Software License
"""

from xml_parser_expat import direct_xml_parser_expat
from xml.parsers import expat
import re

class direct_xml_reader (object):
#
	"""
This class provides a bridge between PHP and XML to read XML on the fly.

@author     direct Netware Group
@copyright  (C) direct Netware Group - All rights reserved
@package    ext_core
@subpackage xml
@since      v1.0.0
@license    http://www.direct-netware.de/redirect.php?licenses;w3c
            W3C (R) Software License
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

	def __init__ (self,f_charset = "UTF-8",f_parse_only = True,f_time = -1,f_timeout_count = 5,f_debug = False):
	#
		"""
Constructor __init__ (direct_xml_reader)

@param f_charset Charset to be added as information to XML output
@param f_parse_only Parse data only
@param f_time Current UNIX timestamp
@param f_timeout_count Retries before timing out
@param f_debug Debug flag
@since v0.1.00
		"""

		if (f_debug): self.debug = [ "xml/#echo(__FILEPATH__)# -xml_reader->__init__ (direct_xml_reader)- (#echo(__LINE__)#)" ]
		else: self.debug = None

		self.data_parser = direct_xml_parser_expat (self,f_debug)

		"""
----------------------------------------------------------------------------
Initiate the array tree cache
----------------------------------------------------------------------------
		"""

		self.data = None
		self.data_cache_node = ""
		self.data_cache_pointer = ""
		self.data_charset = f_charset.upper ()
		self.data_ns = { }
		self.data_ns_compact = { }
		self.data_ns_counter = 0
		self.data_ns_default = { }
		self.data_ns_predefined_compact = { }
		self.data_ns_predefined_default = { }
		self.data_parse_only = f_parse_only
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

	def array2xml (self,f_swgxml_array,f_strict_standard = True):
	#
		"""
Builds recursively a valid XML ouput reflecting the given XML array tree.

@param  f_swgxml_array XML array tree level to work on
@param  f_strict_standard Be standard conform
@return (string) XML output string
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader->array2xml (+f_swgxml_array,+f_strict_standard)- (#echo(__LINE__)#)")
		f_return = ""

		if ((type (f_swgxml_array) == dict) and (len (f_swgxml_array) > 0)):
		#
			for f_swgxml_node in f_swgxml_array:
			#
				f_swgxml_node_array = f_swgxml_array[f_swgxml_node]

				if ("xml.mtree" in f_swgxml_node_array):
				#
					del (f_swgxml_node_array['xml.mtree'])
					f_return += self.array2xml (f_swgxml_node_array,f_strict_standard)
				#
				elif ("xml.item" in f_swgxml_node_array):
				#
					if (self.debug != None): f_return += "\n"
					f_return += self.array2xml_item_encoder (f_swgxml_node_array['xml.item'],False,f_strict_standard)
					if (self.debug != None): f_return += "\n"

					if (re.compile("\\d").match (f_swgxml_node_array['xml.item']['tag']) == None): f_swgxml_node_tag = f_swgxml_node_array['xml.item']['tag']
					else: f_swgxml_node_tag = "digitstart__%s" % f_swgxml_node_array['xml.item']['tag']

					del (f_swgxml_node_array['xml.item'])
					f_return += self.array2xml (f_swgxml_node_array,f_strict_standard)

					if (self.debug != None): f_return += "\n"
					f_return += "</%s>" % f_swgxml_node_tag
				#
				elif (len (f_swgxml_node_array['tag']) > 0):
				#
					if (self.debug != None): f_return += "\n"
					f_return += self.array2xml_item_encoder (f_swgxml_node_array,True,f_strict_standard)
				#
			#
		#

		return f_return.strip ()
	#

	def array2xml_item_encoder (self,f_data,f_close_tag = True,f_strict_standard = True):
	#
		"""
Builds recursively a valid XML ouput reflecting the given XML array tree.

@param  f_data Array containing information about the current item
@param  f_close_tag Output will contain a ending tag if true
@param  f_strict_standard Be standard conform
@return (string) XML output string
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader->array2xml_item_encoder (+f_data,+f_close_tag,+f_strict_standard)- (#echo(__LINE__)#)")
		f_return = ""

		if (f_strict_standard): f_value_attribute_check = False
		else: f_value_attribute_check = True

		if (type (f_data) == dict):
		#
			if (len (f_data['tag']) > 0):
			#
				if (re.compile("\\d").match (f_data['tag']) != None): f_data['tag'] = "digitstart__%s" % f_data['tag']
				f_return += "<%s" % f_data['tag']

				if ("attributes" in f_data):
				#
					for f_key in f_data['attributes']:
					#
						f_type_value = type (f_data['attributes'][f_key])

						if ((f_type_value == int) or (f_type_value == float)): f_value = str (f_data['attributes'][f_key])
						else: f_value = f_data['attributes'][f_key]

						if ((not f_strict_standard) and (not len (f_data['value'])) and (f_key == "value")): f_data['value'] = f_value
						else:
						#
							f_value = f_value.replace ("&","&amp;")
							f_value = f_value.replace ("<","&lt;")
							f_value = f_value.replace (">","&gt;")
							f_value = f_value.replace ('"',"&quot;")
							if (self.data_charset != "UTF-8"): f_value = f_value.encode (self.data_charset)

							f_return += " %s=\"%s\"" % ( f_key,f_value )
						#
					#
				#

				if (("value" in f_data) and ((f_strict_standard) or (len (f_data['value'])> 0))):
				#
					f_type_data_value = type (f_data['value'])

					if ((f_type_data_value == int) or (f_type_data_value == float)): f_data['value'] = str (f_data['value'])
					elif (f_data['value'].find ("&") != -1): f_value_attribute_check = False
					elif (f_data['value'].find ("<") != -1): f_value_attribute_check = False
					elif (f_data['value'].find (">") != -1): f_value_attribute_check = False
					elif (f_data['value'].find ('"') != -1): f_value_attribute_check = False
					elif (re.compile("\\s").search (f_data['value'].replace (" ","_")) != None): f_value_attribute_check = False

					if (f_value_attribute_check):
					#
						if (self.data_charset != "UTF-8"): f_data['value'] = f_data['value'].encode (self.data_charset)
						f_return += " value=\"%s\"" % f_data['value']
					#
				#

				if ((f_value_attribute_check) and (f_close_tag)): f_return += " />"
				else:
				#
					f_return += ">"

					if (("value" in f_data) and (not f_value_attribute_check)):
					#
						if ((f_data['value'].find ("<") < 0) and (f_data['value'].find (">") < 0)):
						#
							f_data['value'] = f_data['value'].replace ("&","&amp;")
							if (self.data_charset != "UTF-8"): f_data['value'] = f_data['value'].encode (self.data_charset)

							f_return += f_data['value']
						#
						else:
						#
							if (f_data['value'].find ("]]>") != -1): f_data['value'] = f_data['value'].replace ("]]>","]]]]><![CDATA[>")
							f_return += "<![CDATA[%s]]>" % f_data['value']
						#
					#
				#

				if ((not f_value_attribute_check) and (f_close_tag)): f_return += "</%s>" % f_data['tag']
			#
		#

		return f_return
	#

	def define_parse_only (self,f_parse_only = True):
	#
		"""
Changes the object behaviour of deleting cached data after parsing is
completed.

@param  f_parse_only Parse data only
@return (boolean) Accepted state
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader->define_parse_only (+f_parse_only)- (#echo(__LINE__)#)")
		f_type = type (f_parse_only)

		if (((f_type == bool) or (f_type == str) or (f_type == unicode)) and (f_parse_only)): self.data_parse_only = True
		elif ((f_parse_only == None) and (not self.data_parse_only)): self.data_parse_only = True
		else: self.data_parse_only = False

		return self.data_parse_only
	#

	def dict_search (self,f_needle,f_haystack):
	#
		"""
Searches haystack for needle. 

@param  f_needle Value to be searched for
@param  f_haystack Dict to search in
@return (mixed) Key on success; False on error
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader->dict_search (%s,+f_haystack)- (#echo(__LINE__)#)" % f_needle)
		f_return = False

		if (f_needle in f_haystack):
		#
			for f_key in f_haystack:
			#
				if (f_haystack[f_key] == f_needle):
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

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader->get ()- (#echo(__LINE__)#)")

		if (self.data == None): return False
		else: return self.data
	#

	def node_add (self,f_node_path,f_value = "",f_attributes = "",f_add_recursively = True):
	#
		"""
Adds a XML node with content - recursively if required.

@param  f_node_path Path to the new node - delimiter is space
@param  f_value Value for the new node
@param  f_attributes Attributes of the node
@param  f_add_recursively True to create the required tree recursively
@return (boolean) False on error
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader->node_add (%s,+f_value,+f_attributes,+f_add_recursively)- (#echo(__LINE__)#)" % f_node_path)
		f_return = False

		f_type_path = type (f_node_path)
		f_type_value = type (f_value)

		if (((f_type_path == str) or (f_type_path == unicode)) and (f_type_value != list) and (f_type_value != dict)):
		#
			f_node_path = self.ns_translate_path (f_node_path)

			if ((len (self.data_cache_node) == 0) or (re.compile("%s" % (re.escape (f_node_path)),re.I).match (self.data_cache_node) == None)):
			#
				f_node_path_done = ""
				f_node_pointer = self.data
			#
			else:
			#
				f_node_path_done = f_node_path
				f_node_path = f_node_path[len (self.data_cache_node):].strip ()
				f_node_pointer = self.data_cache_pointer
			#

			f_nodes_array = f_node_path.split (" ")
			f_continue_check = True
			f_re_attributes_xmlns = re.compile ("xmlns\\:",re.I)
			f_re_node_name_xmlns = re.compile ("^(.+?):(\\w+)$")
			f_re_node_position = re.compile ("^(.+?)\\#(\\d+)$")

			while ((f_continue_check) and (len (f_nodes_array) > 0)):
			#
				f_continue_check = False
				f_node_name = f_nodes_array.pop (0)
				f_result_object = f_re_node_position.search (f_node_name)

				if (f_result_object == None): f_node_position = -1
				else:
				#
					f_node_name = f_result_object.group (1)
					f_node_position = f_result_object.group (2)
				#

				if (len (f_nodes_array) > 0):
				#
					if (f_node_name in f_node_pointer):
					#
						if ("xml.mtree" in f_node_pointer[f_node_name]):
						#
							if (f_node_position >= 0):
							#
								if (f_node_position in f_node_pointer[f_node_name]):
								#
									f_return = True
									f_continue_check = True

									if ((type (f_node_pointer[f_node_name][f_node_position]) != dict) or (not "xml.item" in f_node_pointer[f_node_name][f_node_position])): f_node_pointer[f_node_name][f_node_position] = { "xml.item": f_node_pointer[f_node_name][f_node_position] }
									f_node_pointer = f_node_pointer[f_node_name][f_node_position]
								#
							#
							elif (f_node_pointer[f_node_name]['xml.mtree'] in f_node_pointer[f_node_name]):
							#
								f_return = True
								f_continue_check = True
								f_node_position = f_node_pointer[f_node_name]['xml.mtree']

								if ((type (f_node_pointer[f_node_name][f_node_position]) != dict) or (not "xml.item" in f_node_pointer[f_node_name][f_node_position])): f_node_pointer[f_node_name][f_node_position] = { "xml.item": f_node_pointer[f_node_name][f_node_position] }
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

					if ((not f_continue_check) and (f_add_recursively)):
					#
						if ("level" in f_node_pointer['xml.item']): f_node_level = (1 + f_node_pointer['xml.item']['level'])
						else: f_node_level = 1

						f_node_array = { "tag": f_node_name,"level": f_node_level,"xmlns": { } }
						if ("xmlns" in f_node_pointer['xml.item']): f_node_array['xmlns'] = f_node_pointer['xml.item']['xmlns']

						f_continue_check = True
						f_node_pointer[f_node_name] = { "xml.item": f_node_array }
						f_node_pointer = f_node_pointer[f_node_name]
					#

					if (len (f_node_path_done) > 0): f_node_path_done += " "
					f_node_path_done += f_node_name
				#
				else:
				#
					f_node_array = { "tag": f_node_name,"value": f_value,"xmlns": { } }
					f_node_ns_check = True
					f_node_ns_name = ""
					if (("xml.item" in f_node_pointer) and ("xmlns" in f_node_pointer['xml.item'])): f_node_array['xmlns'] = f_node_pointer['xml.item']['xmlns']

					if ((type (f_attributes) == dict) and (len (f_attributes) > 0)):
					#
						if ("xmlns" in f_attributes):
						#
							if (len (f_attributes['xmlns']) > 0):
							#
								if (f_attributes['xmlns'] in self.data_ns_default):
								#
									f_node_array['xmlns']['@'] = self.data_ns_default[f_attributes['xmlns']]
									f_node_ns_name = "%s:%s" % ( self.data_ns_default[f_attributes['xmlns']],f_node_name )
								#
								else:
								#
									self.data_ns_counter += 1
									self.data_ns_default[f_attributes['xmlns']] = self.data_ns_counter
									self.data_ns_compact[self.data_ns_counter] = f_attributes['xmlns']
									f_node_array['xmlns']['@'] = self.data_ns_counter
									f_node_ns_name = "%s:%s" % ( self.data_ns_counter,f_node_name )
								#
							#
							elif ("@" in f_node_array['xmlns']): del (f_node_array['xmlns']['@'])
						#

						for f_key in f_attributes:
						#
							f_value = f_attributes[f_key]

							if (f_re_attributes_xmlns.match (f_key)):
							#
								f_ns_name = f_key[6:]

								if (len (f_value) > 0):
								#
									if (f_value in self.data_ns_default): f_node_array['xmlns'][f_ns_name] = self.data_ns_default[f_value]
									else: f_node_array['xmlns'][f_ns_name] = f_value
								#
								elif (f_ns_name in f_node_array['xmlns']): del (f_node_array['xmlns'][f_ns_name])
							#
						#

						f_node_array['attributes'] = f_attributes
					#

					f_result_object = f_re_node_name_xmlns.search (f_node_name)

					if (f_result_object != None):
					#
						if ((f_result_object.group (1) in f_node_array['xmlns']) and (type (f_node_array['xmlns'][f_result_object.group (1)]) == int)): f_node_ns_name = "%s:%s" % ( f_node_array['xmlns'][f_result_object.group (1)],f_result_object.group (2) )
						else: f_node_ns_check = False
					#
					elif ("@" in f_node_array['xmlns']): f_node_ns_name = "%s:%s" % ( f_node_array['xmlns']['@'],f_node_name )
					else: f_node_ns_check = False

					if (f_node_ns_check):
					#
						if (len (f_node_path_done) > 0):
						#
							self.data_ns_predefined_compact["%s %s" % ( f_node_path_done,f_node_name )] = "%s %s" % ( self.data_ns_predefined_compact[f_node_path_done],f_node_ns_name )
							self.data_ns_predefined_default[self.data_ns_predefined_compact["%s %s" % ( f_node_path_done,f_node_name )]] = "%s %s" % ( f_node_path_done,f_node_name )
						#
						else:
						#
							self.data_ns_predefined_compact[f_node_name] = f_node_ns_name
							self.data_ns_predefined_default[f_node_ns_name] = f_node_name
						#
					#
					else:
					#
						if (len (f_node_path_done) > 0):
						#
							self.data_ns_predefined_compact["%s %s" % ( f_node_path_done,f_node_name )] = "%s %s" % ( self.data_ns_predefined_compact[f_node_path_done],f_node_name )
							self.data_ns_predefined_default[self.data_ns_predefined_compact["%s %s" % ( f_node_path_done,f_node_name )]] = "%s %s" % ( f_node_path_done,f_node_name )
						#
						else:
						#
							self.data_ns_predefined_compact[f_node_name] = f_node_name
							self.data_ns_predefined_default[f_node_name] = f_node_name
						#
					#

					if (f_node_name in f_node_pointer):
					#
						if ((type (f_node_pointer[f_node_name]) != dict) or (not "xml.mtree" in f_node_pointer[f_node_name])): f_node_pointer[f_node_name] = { "xml.mtree": 1,0: f_node_pointer[f_node_name],1: f_node_array }
						else:
						#
							f_node_pointer[f_node_name]['xml.mtree'] += 1
							f_node_pointer[f_node_name][f_node_pointer[f_node_name]['xml.mtree']] = f_node_array
						#
					#
					else: f_node_pointer[f_node_name] = f_node_array

					f_return = True
				#
			#
		#

		return f_return
	#

	def ns_register (self,f_ns,f_uri):
	#
		"""
Registers a namespace (URI) for later use with this XML bridge class.

@param f_ns Output relevant namespace definition
@param f_uri Uniform Resource Identifier
@since v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader->ns_register (%s,%s)- (#echo(__LINE__)#)" % (f_ns,f_uri ))
		self.data_ns[f_ns] = f_uri

		if (not f_uri in self.data_ns_default):
		#
			self.data_ns_counter += 1
			self.data_ns_default[f_uri] = self.data_ns_counter
			self.data_ns_compact[self.data_ns_counter] = f_uri
		#
	#

	def ns_translate (self,f_node):
	#
		"""
Translates the tag value if a predefined namespace matches. The translated
tag will be saved as "tag_ns" and "tag_parsed".

@param  f_node XML array node
@return (array) Checked XML array node
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader->ns_translate (+f_node)- (#echo(__LINE__)#)")
		f_return = f_node

		if ((type (f_node) == dict) and ("tag" in f_node)):
		#
			f_return['tag_ns'] = ""
			f_return['tag_parsed'] = f_node['tag']
			f_result_object = re.compile("^(.+?):(\\w+)$").search (f_node['tag'])

			if ((f_result_object != None) and ("xmlns" in f_node) and (type (f_node['xmlns']) == dict)):
			#
				if ((f_result_object.group (1) in f_node['xmlns']) and (f_node['xmlns'][f_result_object.group (1)] in self.data_ns_compact)):
				#
					f_tag_ns = self.dict_search (self.data_ns_compact[f_node['xmlns'][f_result_object.group (1)]],self.data_ns)

					if (type (f_tag_ns) != bool):
					#
						f_return['tag_ns'] = f_tag_ns
						f_return['tag_parsed'] = "%s:%s" % ( f_tag_ns,f_result_object.group (2) )
					#
				#
			#
		#

		return f_return
	#

	def ns_translate_path (self,f_node_path):
	#
		"""
Checks input path for predefined namespaces converts it to the internal
path.

@param  f_node_path Path to the new node - delimiter is space
@return (string) Output node path
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader->ns_translate_path (%s)- (#echo(__LINE__)#)" % f_node_path)
		f_return = f_node_path

		f_nodes_array = f_node_path.split (" ")
		f_node_path = ""
		f_re_node_name_xmlns = re.compile ("^(.+?):(\\w+)$")

		while (len (f_nodes_array) > 0):
		#
			f_node_name = f_nodes_array.pop (0)
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
						if (self.data_ns[f_result_object.group (1)] in self.data_ns_default): f_node_path += "%s:%s" % ( self.data_ns_default[self.data_ns[f_result_object.group (1)]],f_result_object.group (2) )
						else: f_node_path += "%s:%s" % ( f_result_object.group (1),f_result_object.group (2) )
					#
					else: f_node_path += "%s:%s" % ( f_result_object.group (1),f_result_object.group (2) )
				#
			#
		#

		if (f_node_path in self.data_ns_predefined_default): f_return = self.data_ns_predefined_default[f_node_path]
		return f_return
	#

	def ns_unregister (self,f_ns = ""):
	#
		"""
Unregisters a namespace or clears the cache (if $f_ns is empty).

@param f_ns Output relevant namespace definition
@since v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader->ns_unregister (%s)- (#echo(__LINE__)#)" % f_ns)

		if (len (f_ns) > 0):
		#
			if (f_ns in self.data_ns):
			#
				del (self.data_ns_compact[self.data_ns_default[self.data_ns[f_ns]]]);
				del (self.data_ns_default[self.data_ns[f_ns]]);
				del (self.data_ns[f_ns]);
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

	def set (self,f_swgxml_array,f_overwrite = False):
	#
		"""
"Imports" a sWG XML tree into the cache.

@param  f_swgxml_array Input array
@param  f_overwrite True to overwrite the current (non-empty) cache
@return (boolean) True on success
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader->set (+f_swgxml_array,+f_overwrite)- (#echo(__LINE__)#)")
		f_return = False

		if (((self.data == None) or (f_overwrite)) and (type (f_swgxml_array) == dict)):
		#
			self.data = f_swgxml_array
			f_return = True
		#

		return f_return
	#

	def xml2array (self,f_data,f_treemode = True,f_strict_standard = True):
	#
		"""
Converts XML data into a multi-dimensional or merged array ...

@param  f_data Input XML data
@param  f_strict_standard Be standard conform
@param  f_treemode Create a multi-dimensional result
@return (mixed) Multi-dimensional XML tree or merged array; False on error
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_reader->xml2array (+f_data,+f_treemode,+f_strict_standard)- (#echo(__LINE__)#)")
		f_return = False

		try:
		#
			if (re.compile("<\\?xml(.+?)encoding=").search (f_data) == None):
			#
				f_parser_pointer = expat.ParserCreate ("UTF-8")
				if (type (f_data) == unicode): f_data = f_data.encode ("utf-8")
			#
			else: f_parser_pointer = expat.ParserCreate ()
		#
		except Exception,f_handled_exception: f_parser_pointer = None

		if (f_parser_pointer != None):
		#
			if (f_treemode):
			#
				self.data_parser.define_mode ("tree")
				self.data_parser.define_strict_standard (f_strict_standard)

				f_parser_pointer.CharacterDataHandler = self.data_parser.expat_cdata
				f_parser_pointer.StartElementHandler = self.data_parser.expat_element_start
				f_parser_pointer.EndElementHandler = self.data_parser.expat_element_end
				f_parser_pointer.Parse (f_data,True)

				f_return = self.data_parser.xml2array_expat ()
			#
			else:
			#
				self.data_parser.define_mode ("merged")

				f_parser_pointer.CharacterDataHandler = self.data_parser.expat_merged_cdata
				f_parser_pointer.StartElementHandler = self.data_parser.expat_merged_element_start
				f_parser_pointer.EndElementHandler = self.data_parser.expat_merged_element_end
				f_parser_pointer.Parse (f_data,True)

				f_return = self.data_parser.xml2array_expat_merged ()
			#
		#

		if ((f_treemode) and (self.data_parse_only)):
		#
			self.data = None
			self.ns_unregister ()
		#

		return f_return
	#
#

##j## EOF