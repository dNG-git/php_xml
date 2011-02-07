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
@license    http://www.direct-netware.de/redirect.php?licenses;w3c
            W3C (R) Software License
"""
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

import re

from .xml_reader import direct_xml_reader

try: _unicode_object = { "type": unicode,"str": unicode.encode }
except: _unicode_object = { "type": bytes,"str": bytes.decode }

class direct_xml_writer (direct_xml_reader):
#
	"""
This class extends the bridge between Python and XML to work with XML and
create valid documents.

@author     direct Netware Group
@copyright  (C) direct Netware Group - All rights reserved
@package    ext_core
@subpackage xml
@since      v1.0.0
@license    http://www.direct-netware.de/redirect.php?licenses;w3c
            W3C (R) Software License
	"""

	"""
----------------------------------------------------------------------------
Extend the class
----------------------------------------------------------------------------
	"""

	def __init__ (self,xml_charset = "UTF-8",current_time = -1,timeout_count = 5,debug = False):
	#
		"""
Constructor __init__ (direct_xml_writer)

@param xml_charset Charset to be added as information to XML output
@param current_time Current UNIX timestamp
@param timeout_count Retries before timing out
@param debug Debug flag
@since v0.1.00
		"""

		direct_xml_reader.__init__ (self,xml_charset,False,current_time,timeout_count,debug)
	#

	def del_direct_xml_writer (self):
	#
		"""
Destructor del_direct_xml_writer (direct_xml_writer)

@since v0.1.00
		"""

		self.del_direct_xml_reader ()
	#

	def array_import (self,data_dict,overwrite = False):
	#
		"""
Read and convert a simple multi-dimensional dict into our XML tree.

@param  data_dict Input array
@param  overwrite True to overwrite the current (non-empty) cache
@return (bool) True on success
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler.array_import (data_dict,overwrite)- (#echo(__LINE__)#)")
		f_return = False

		if ((self.data == None) or (len (self.data) < 1) or (overwrite)):
		#
			f_swgxml_dict = self.array_import_walker (data_dict)
			self.data = f_swgxml_dict
			f_return = True
		#

		return f_return
	#

	def array_import_walker (self,data_dict,xml_level = 1):
	#
		"""
Read and convert a single dimension of an dictionary for our XML tree.

@param  data_dict Input array
@param  xml_level Current level of an multi-dimensional array
@return (dict) Output dictionary
@since  v0.1.00
		"""

		global _unicode_object
		if (type (xml_level) == _unicode_object['type']): xml_level = _unicode_object['str'] (xml_level,"utf-8")

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler.array_import_walker (data_dict,{0})- (#echo(__LINE__)#)".format (xml_level))
		f_return = { }

		if (type (data_dict) == dict):
		#
			for f_key in data_dict:
			#
				f_value = data_dict[f_key]

				if (len (f_key) > 0):
				#
					f_type = type (f_value)

					if (f_type == dict):
					#
						f_node_dict = { "xml.item": { "tag": f_key,"level": xml_level,"xmlns": { } } }
						f_node_dict.update (self.array_import_walker (f_value,(xml_level + 1)))
						f_return[f_key] = f_node_dict
					#
					elif (f_type != list): f_return[f_key] = { "tag": f_key,"value": f_value,"xmlns": { } }
				#
			#
		#

		return f_return
	#

	def cache_export (self,flush = False,strict_standard = True):
	#
		"""
Convert the cached XML tree into a XML string.

@param  flush True to delete the cache content
@param  strict_standard Be standard conform
@return (str) Result string
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler.cache_export (flush,strict_standard)- (#echo(__LINE__)#)")

		if ((self.data == None) or (len (self.data) < 1)): f_return = ""
		else:
		#
			f_return = self.array2xml (self.data,strict_standard)
			if (flush): self.data = { }
		#

		return f_return
	#

	def node_cache_pointer (self,node_path):
	#
		"""
Set the cache pointer to a specific node.

@param  node_path Path to the node - delimiter is space
@return (bool) True on success
@since  v0.1.00
		"""

		global _unicode_object
		if (type (node_path) == _unicode_object['type']): node_path = _unicode_object['str'] (node_path,"utf-8")

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler.node_cache_pointer ({0})- (#echo(__LINE__)#)".format (node_path))
		f_return = False

		if (type (node_path) == str):
		#
			f_node_path = self.ns_translate_path (node_path)

			if (f_node_path == self.data_cache_node): f_return = True
			else:
			#
				f_node_pointer = self.node_get_pointer (f_node_path)

				if (type (f_node_pointer) == dict):
				#
					f_return = True
					self.data_cache_node = f_node_path
					self.data_cache_pointer = f_node_pointer
				#
			#
		#

		return f_return
	#

	def node_change_attributes (self,node_path,attributes):
	#
		"""
Change the attributes of a specified node. Note: XMLNS updates must be
handled by the calling code.

@param  node_path Path to the new node - delimiter is space
@param  attributes Attributes of the node
@return (bool) False on error
@since  v0.1.00
		"""

		global _unicode_object
		if (type (node_path) == _unicode_object['type']): node_path = _unicode_object['str'] (node_path,"utf-8")

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler.node_change_attributes ({0},+attributes)- (#echo(__LINE__)#)".format (node_path))
		f_return = False

		if ((type (node_path) == str) and (type (attributes) == dict)):
		#
			f_node_path = self.ns_translate_path (node_path)
			f_node_pointer = self.node_get_pointer (f_node_path)

			if (type (f_node_pointer) == dict):
			#
				if ("xml.item" in f_node_pointer): f_node_pointer = f_node_pointer['xml.item']
				f_node_pointer['attributes'] = attributes
				f_return = True
			#
		#

		return f_return
	#

	def node_change_value (self,node_path,value):
	#
		"""
Change the value of a specified node.

@param  node_path Path to the new node - delimiter is space
@param  value Value for the new node
@return (bool) False on error
@since  v0.1.00
		"""

		global _unicode_object
		if (type (node_path) == _unicode_object['type']): node_path = _unicode_object['str'] (node_path,"utf-8")

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler.node_change_value ({0},+value)- (#echo(__LINE__)#)".format (node_path))
		f_return = False

		f_type_value = type (value)

		if ((type (node_path) == str) and (f_type_value != list) and (f_type_value != dict)):
		#
			f_node_path = xself.ns_translate_path (node_path)
			f_node_pointer = self.node_get_pointer (f_node_path)

			if (type (f_node_pointer) == dict):
			#
				if ("xml.item" in f_node_pointer): f_node_pointer['xml.item']['value'] = value
				else: f_node_pointer['value'] = value

				f_return = True
			#
		#

		return f_return
	#

	def node_count (self,node_path):
	#
		"""
Count the occurrence of a specified node.

@param  node_path Path to the node - delimiter is space
@return (int) Counted number off matching nodes
@since  v0.1.00
		"""

		global _unicode_object
		if (type (node_path) == _unicode_object['type']): node_path = _unicode_object['str'] (node_path,"utf-8")

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler.node_count ({0})- (#echo(__LINE__)#)".format (node_path))
		f_return = 0

		if (type (node_path) == str):
		#
			"""
----------------------------------------------------------------------------
Get the parent node of the target.
----------------------------------------------------------------------------
			"""

			f_node_path = self.ns_translate_path (node_path)
			f_node_path_list = f_node_path.split (" ")

			if (len (f_node_path_list) > 1):
			#
				f_node_name = f_node_path_list.pop ()
				f_node_path = " ".join (f_node_path_list)
				f_node_pointer = self.node_get_pointer (f_node_path)
			#
			else:
			#
				f_node_name = f_node_path
				f_node_pointer = self.data
			#

			if ((type (f_node_pointer) == dict) and (f_node_name in f_node_pointer)):
			#
				if ("xml.mtree" in f_node_pointer[f_node_name]): f_return = ((len (f_node_pointer[f_node_name])) - 1)
				else: f_return = 1
			#
		#

		return f_return
	#

	def node_get (self,node_path,remove_metadata = True):
	#
		"""
Read a specified node including all children if applicable.

@param  node_path Path to the node - delimiter is space
@param  remove_metadata False to not remove the xml.item node
@return (mixed) XML node array on success; false on error
@since  v0.1.00
		"""

		global _unicode_object
		if (type (node_path) == _unicode_object['type']): node_path = _unicode_object['str'] (node_path,"utf-8")

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler.node_get ({0})- (#echo(__LINE__)#)".format (node_path))
		f_return = False

		if (type (node_path) == str):
		#
			f_node_path = self.ns_translate_path (node_path)
			f_node_pointer = self.node_get_pointer (f_node_path)

			if (type (f_node_pointer) == dict):
			#
				f_return = f_node_pointer
				if ((remove_metadata) and ("xml.item" in f_return)): del (f_return['xml.item'])
			#
		#

		return f_return
	#

	def node_get_pointer (self,node_path):
	#
		"""
Returns the pointer to a specific node.

@param  node_path Path to the node - delimiter is space
@return (mixed) XML node pointer on success; false on error
@since  v0.1.00
		"""

		global _unicode_object
		if (type (node_path) == _unicode_object['type']): node_path = _unicode_object['str'] (node_path,"utf-8")

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler.node_get_pointer ({0})- (#echo(__LINE__)#)".format (node_path))
		f_return = False

		if (type (node_path)):
		#
			if ((len (self.data_cache_node) == 0) or (re.compile("{0}".format (re.escape (self.data_cache_node)),re.I).match (node_path) == None)): f_node_pointer = self.data
			else:
			#
				node_path = node_path[len (self.data_cache_node):].strip ()
				f_node_pointer = self.data_cache_pointer
			#

			f_continue_check = True

			if (len (node_path)): f_node_path_list = node_path.split (" ")
			else: f_node_path_list = [ ]

			f_re_node_position = re.compile ("^(.+?)\\#(\\d+)$")

			while ((f_continue_check) and (len (f_node_path_list) > 0)):
			#
				f_continue_check = False
				f_node_name = f_node_path_list.pop (0)
				f_result_object = f_re_node_position.search (f_node_name)

				if (f_result_object == None): f_node_position = -1
				else:
				#
					f_node_name = f_result_object.group (1)
					f_node_position = int (f_result_object.group (2))
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
								f_node_pointer = f_node_pointer[f_node_name][f_node_position]
							#
						#
						elif (f_node_pointer[f_node_name]['xml.mtree'] in f_node_pointer[f_node_name]):
						#
							f_continue_check = True
							f_node_pointer = f_node_pointer[f_node_name][f_node_pointer[f_node_name]['xml.mtree']]
						#
					#
					else:
					#
						f_continue_check = True
						f_node_pointer = f_node_pointer[f_node_name]
					#
				#
			#

			if (f_continue_check): f_return = f_node_pointer
		#

		return f_return
	#

	def node_remove (self,node_path):
	#
		"""
Remove a node and all children if applicable.

@param  node_path Path to the node - delimiter is space
@return (bool) False on error
@since  v0.1.00
		"""

		global _unicode_object
		if (type (node_path) == _unicode_object['type']): node_path = _unicode_object['str'] (node_path,"utf-8")

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler.node_remove ({0})- (#echo(__LINE__)#)".format (node_path))
		f_return = False

		if (type (node_path) == str):
		#
			"""
----------------------------------------------------------------------------
Get the parent node of the target.
----------------------------------------------------------------------------
			"""

			f_node_path = self.ns_translate_path (node_path)
			f_node_path_list = f_node_path.split (" ")

			if (len (f_node_path_list) > 1):
			#
				f_node_name = f_node_path_list.pop ()
				f_node_path = " ".join (f_node_path_list)
				f_node_pointer = self.node_get_pointer (f_node_path)

				if ((len (self.data_cache_node)) and (self.data_cache_node.find (f_node_path) == 0)):
				#
					self.data_cache_node = "";
					self.data_cache_pointer = self.data
				#
			#
			else:
			#
				f_node_name = f_node_path
				f_node_pointer = self.data

				self.data_cache_node = "";
				self.data_cache_pointer = self.data
			#

			if (type (f_node_pointer) == dict):
			#
				f_result_object = re.compile("^(.+?)\\#(\\d+)$").search (f_node_name)

				if (f_result_object == None): f_node_position = -1
				else:
				#
					f_node_name = f_result_object.group (1)
					f_node_position = int (f_result_object.group (2))
				#

				if (f_node_name in f_node_pointer):
				#
					if ("xml.mtree" in f_node_pointer[f_node_name]):
					#
						if (f_node_position >= 0):
						#
							if (f_node_position in f_node_pointer[f_node_name]):
							#
								del (f_node_pointer[f_node_name][f_node_position])
								f_return = True
							#
						#
						elif (f_node_pointer[f_node_name]['xml.mtree'] in f_node_pointer[f_node_name]):
						#
							del (f_node_pointer[f_node_name][f_node_pointer[f_node_name]['xml.mtree']])
							f_return = True
						#

						"""
----------------------------------------------------------------------------
Update the mtree counter or remove it if applicable.
----------------------------------------------------------------------------
						"""

						if (f_return):
						#
							f_node_pointer[f_node_name]['xml.mtree'] -= 1

							if (f_node_pointer[f_node_name]['xml.mtree'] > 0):
							#
								f_node_dict = { "xml.mtree": f_node_pointer[f_node_name]['xml.mtree'] }
								del (f_node_pointer[f_node_name]['xml.mtree'])

								f_node_position = 0

								for f_key in f_node_pointer[f_node_name]:
								#
									f_value = f_node_pointer[f_node_name][f_key]
									f_node_dict[f_node_position] = f_value
									f_node_position += 1
								#
							#
							else:
							#
								del (f_node_pointer[f_node_name]['xml.mtree'])
								f_node_pointer[f_node_name] = f_node_pointer[f_node_name].pop ()
							#
						#
					#
					else:
					#
						del (f_node_pointer[f_node_name])
						f_return = True
					#
				#
			#
		#

		return f_return
	#

	def ns_get_uri (self,data):
	#
		"""
Returns the registered namespace (URI) for a given XML NS or node name
containing the registered XML NS.

@param  data XML NS or node name
@return (str) Namespace (URI)
@since  v0.1.00
		"""

		global _unicode_object
		if (type (data) == _unicode_object['type']): data = _unicode_object['str'] (data,"utf-8")

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler.ns_get_uri ({0})- (#echo(__LINE__)#)".format (data))
		f_return = ""

		f_result_object = re.compile("^(\\w+):(\\w+)$").search (data)

		if (f_result_object != None):
		#
			if (f_result_object.group (1) in self.data_ns): f_return = self.data_ns[f_result_object.group (1)]
		#
		elif (data in self.data_ns): f_return = self.data_ns[data]

		return f_return
	#
#

##j## EOF