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

from xml_reader import direct_xml_reader
import re

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

	def __init__ (self,f_charset = "UTF-8",f_time = -1,f_timeout_count = 5,f_debug = False):
	#
		"""
Constructor __init__ (direct_xml_writer)

@param f_charset Charset to be added as information to XML output
@param f_time Current UNIX timestamp
@param f_timeout_count Retries before timing out
@param f_debug Debug flag
@since v0.1.00
		"""

		direct_xml_reader.__init__ (self,f_charset,False,f_time,f_timeout_count,f_debug)
	#

	def del_direct_xml_writer (self):
	#
		"""
Destructor del_direct_xml_writer (direct_xml_writer)

@since v0.1.00
		"""

		self.del_direct_xml_reader ()
	#

	def array_import (self,f_array,f_overwrite = False):
	#
		"""
Read and convert a simple multi-dimensional array into our XML tree.

@param  f_array Input array
@param  f_overwrite True to overwrite the current (non-empty) cache
@return (boolean) True on success
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler->array_import (+f_array,+f_overwrite)- (#echo(__LINE__)#)")
		f_return = False

		if ((self.data == None) or (len (self.data) < 1) or (f_overwrite)):
		#
			f_array = self.array_import_walker (f_array)
			self.data = f_array
			f_return = True
		#

		return f_return
	#

	def array_import_walker (self,f_array,f_level = 1):
	#
		"""
Read and convert a single dimensional of an array for our XML tree.

@param  f_array Input array
@param  f_level Current level of an multi-dimensional array
@return (array) Output Array
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler->array_import_walker (+f_array,%s)- (#echo(__LINE__)#)" % f_level)
		f_return = { }

		if (type (f_array) == dict):
		#
			for f_key in f_array:
			#
				f_value = f_array[f_key]

				if (len (f_key) > 0):
				#
					f_type = type (f_value)

					if (f_type == dict):
					#
						f_node_array = { "xml.item": { "tag": f_key,"level": f_level,"xmlns": { } } }
						f_node_array.update (self.array_import_walker (f_value,(f_level + 1)))
						f_return[f_key] = f_node_array
					#
					elif (f_type != list): f_return[f_key] = { "tag": f_key,"value": f_value,"xmlns": { } }
				#
			#
		#

		return f_return
	#

	def cache_export (self,f_flush = False,f_strict_standard = True):
	#
		"""
Convert the cached XML tree into a XML string.

@param  f_flush True to delete the cache content
@param  f_strict_standard Be standard conform
@return (string) Result string
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler->cache_export (+f_flush,+f_strict_standard)- (#echo(__LINE__)#)")

		if ((self.data == None) or (len (self.data) < 1)): f_return = ""
		else:
		#
			f_return = self.array2xml (self.data,f_strict_standard)
			if (f_flush): self.data = { }
		#

		return f_return
	#

	def node_cache_pointer (self,f_node_path):
	#
		"""
Set the cache pointer to a specific node.

@param  f_node_path Path to the node - delimiter is space
@return (boolean) True on success
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler->node_cache_pointer (%s)- (#echo(__LINE__)#)" % f_node_path)
		f_return = False

		f_type = type (f_node_path)

		if ((f_type == str) or (f_type == unicode)):
		#
			f_node_path = self.ns_translate_path (f_node_path)

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

	def node_change_attributes (self,f_node_path,f_attributes):
	#
		"""
Change the attributes of a specified node. Note: XMLNS updates must be
handled by the calling code.

@param  f_node_path Path to the new node - delimiter is space
@param  f_attributes Attributes of the node
@return boolean False on error
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler->node_change_attributes (%s,+f_attributes)- (#echo(__LINE__)#)" % f_node_path)
		f_return = False

		f_type = type (f_node_path)

		if (((f_type == str) or (f_type == unicode)) and (type (f_attributes) == dict)):
		#
			f_node_path = self.ns_translate_path (f_node_path)
			f_node_pointer = self.node_get_pointer (f_node_path)

			if (type (f_node_pointer) == dict):
			#
				if ("xml.item" in f_node_pointer): f_node_pointer = f_node_pointer['xml.item']
				f_node_pointer['attributes'] = f_attributes
				f_return = True
			#
		#

		return f_return
	#

	def node_change_value (self,f_node_path,f_value):
	#
		"""
Change the value of a specified node.

@param  f_node_path Path to the new node - delimiter is space
@param  f_value Value for the new node
@return (boolean) False on error
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler->node_change_value (%s,+f_value)- (#echo(__LINE__)#)" % f_node_path)
		f_return = False

		f_type_path = type (f_node_path)
		f_type_value = type (f_value)

		if (((f_type_path == str) or (f_type_path == unicode)) and (f_type_value != list) and (f_type_value != dict)):
		#
			f_node_path = xself.ns_translate_path (f_node_path)
			f_node_pointer = self.node_get_pointer (f_node_path)

			if (type (f_node_pointer) == dict):
			#
				if ("xml.item" in f_node_pointer): f_node_pointer['xml.item']['value'] = f_value
				else: f_node_pointer['value'] = f_value

				f_return = True
			#
		#

		return f_return
	#

	def node_count (self,f_node_path):
	#
		"""
Count the occurrence of a specified node.

@param  f_node_path Path to the node - delimiter is space
@return (integer) Counted number off matching nodes
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler->node_count (%s)- (#echo(__LINE__)#)" % f_node_path)
		f_return = 0

		f_type = type (f_node_path)

		if ((f_type == str) or (f_type == unicode)):
		#
			"""
----------------------------------------------------------------------------
Get the parent node of the target.
----------------------------------------------------------------------------
			"""

			f_node_path = self.ns_translate_path (f_node_path)
			f_node_path_array = f_node_path.split (" ")

			if (len (f_node_path_array) > 1):
			#
				f_node_name = f_node_path_array.pop ()
				f_node_path = " ".join (f_node_path_array)
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

	def node_get (self,f_node_path,f_remove_metadata = True):
	#
		"""
Read a specified node including all children if applicable.

@param  f_node_path Path to the node - delimiter is space
@param  f_remove_metadata False to not remove the xml.item node
@return (mixed) XML node array on success; false on error
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler->node_get (%s)- (#echo(__LINE__)#)" % f_node_path)
		f_return = False

		f_type = type (f_node_path)

		if ((f_type == str) or (f_type == unicode)):
		#
			f_node_path = self.ns_translate_path (f_node_path)
			f_node_pointer = self.node_get_pointer (f_node_path)

			if (type (f_node_pointer) == dict):
			#
				f_return = f_node_pointer
				if ((f_remove_metadata) and ("xml.item" in f_return)): del (f_return['xml.item'])
			#
		#

		return f_return
	#

	def node_get_pointer (self,f_node_path):
	#
		"""
Returns the pointer to a specific node.

@param  f_node_path Path to the node - delimiter is space
@return (mixed) XML node pointer on success; false on error
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler->node_get_pointer (%s)- (#echo(__LINE__)#)" % f_node_path)
		f_return = False

		f_type = type (f_node_path)

		if ((f_type == str) or (f_type == unicode)):
		#
			if ((len (self.data_cache_node) == 0) or (re.compile("%s" % (re.escape (self.data_cache_node)),re.I).match (f_node_path) == None)): f_node_pointer = self.data
			else:
			#
				f_node_path = f_node_path[len (self.data_cache_node):].strip ()
				f_node_pointer = self.data_cache_pointer
			#

			f_continue_check = True

			if (len (f_node_path)): f_node_path_array = f_node_path.split (" ")
			else: f_node_path_array = [ ]

			f_re_node_position = re.compile ("^(.+?)\\#(\\d+)$")

			while ((f_continue_check) and (len (f_node_path_array) > 0)):
			#
				f_continue_check = False
				f_node_name = f_node_path_array.pop (0)
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

	def node_remove (self,f_node_path):
	#
		"""
Remove a node and all children if applicable.

@param  f_node_path Path to the node - delimiter is space
@return (boolean) False on error
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler->node_remove (%s)- (#echo(__LINE__)#)" % f_node_path)
		f_return = False

		f_type = type (f_node_path)

		if ((f_type == str) or (f_type == unicode)):
		#
			"""
----------------------------------------------------------------------------
Get the parent node of the target.
----------------------------------------------------------------------------
			"""

			f_node_path = self.ns_translate_path (f_node_path)
			f_node_path_array = f_node_path.split (" ")

			if (len (f_node_path_array) > 1):
			#
				f_node_name = f_node_path_array.pop ()
				f_node_path = " ".join (f_node_path_array)
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
								f_node_array = { "xml.mtree": f_node_pointer[f_node_name]['xml.mtree'] }
								del (f_node_pointer[f_node_name]['xml.mtree'])

								f_node_position = 0

								for f_key in f_node_pointer[f_node_name]:
								#
									f_value = f_node_pointer[f_node_name][f_key]
									f_node_array[f_node_position] = f_value
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

	def ns_get_uri (self,f_input):
	#
		"""
Returns the registered namespace (URI) for a given XML NS or node name
containing the registered XML NS.

@param  f_input XML NS or node name
@return (string) Namespace (URI)
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_handler->ns_get_uri (%s)- (#echo(__LINE__)#)" % f_input)
		f_return = ""

		f_result_object = re.compile("^(\\w+):(\\w+)$").search (f_input)

		if (f_result_object != None):
		#
			if (f_result_object.group (1) in self.data_ns): f_return = self.data_ns[f_result_object.group (1)]
		#
		elif (f_input in self.data_ns): f_return = self.data_ns[f_input]

		return f_return
	#
#

##j## EOF