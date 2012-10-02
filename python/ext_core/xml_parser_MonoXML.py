# -*- coding: utf-8 -*-
##j## BOF

"""
XML (Extensible Markup Language) is the easiest way to use a descriptive
language for controlling applications locally and world wide.
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

from System.Xml import XmlNodeType
from time import time

try: _unicode_object = { "type": unicode,"str": unicode.encode }
except: _unicode_object = { "type": bytes,"str": bytes.decode }

class direct_xml_parser_MonoXML (object):
#
	"""
This implementation supports XmlNodeReader for XML parsing.

:author:     direct Netware Group
:copyright:  direct Netware Group - All rights reserved
:package:    ext_core
:subpackage: xml
:since:      v0.1.00
:license:    http://www.direct-netware.de/redirect.php?licenses;mpl2
             Mozilla Public License, v. 2.0
	"""

	debug = None
	"""
Debug message container
	"""
	parser = None
	"""
Container for the XML document
	"""
	time = -1
	"""
Current UNIX timestamp
	"""
	timeout_count = 5
	"""
Retries before timing out
	"""

	"""
----------------------------------------------------------------------------
Construct the class
----------------------------------------------------------------------------
	"""

	def __init__ (self,parser,current_time = -1,timeout_count = 5,debug = False):
	#
		"""
Constructor __init__ (direct_xml_parser_MonoXML)

:param parser: Container for the XML document
:param current_time: Current UNIX timestamp
:param timeout_count: Retries before timing out
:param debug: Debug flag

:since: v0.1.00
		"""

		if (debug): self.debug = [ "xml/#echo(__FILEPATH__)# -xml_parser.__init__ (direct_xml_parser_MonoXML)- (#echo(__LINE__)#)" ]
		else: self.debug = None

		"""
----------------------------------------------------------------------------
Connect to the Python container for the XML document
----------------------------------------------------------------------------
		"""

		self.parser = parser

		if (current_time < 0): self.time = -1
		else: self.time = current_time

		if (timeout_count == None): self.timeout_count = 5
		else: self.timeout_count = timeout_count
	#

	def __del__ (self):
	#
		"""
Destructor __del__ (direct_xml_parser_MonoXML)

:since: v0.1.00
		"""

		self.del_direct_xml_parser_MonoXML ()
	#

	def del_direct_xml_parser_MonoXML (self):
	#
		"""
Destructor del_direct_xml_parser_MonoXML (direct_xml_parser_MonoXML)

:since: v0.1.00
		"""

		self.parser = None
	#

	def xml2array_MonoXML (self,XmlNodeReader,strict_standard = True):
	#
		"""
Adds the result of an expat parsing operation to the defined XML instance if
the parser completed its work.

:param XmlNodeReader: XmlNodeReader object
:param strict_standard: Be standard conform

:return: (dict) Multi-dimensional XML tree
:since:  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_parser.xml2array_MonoXML (XmlNodeReader,strict_standard)- (#echo(__LINE__)#)")
		f_return = { }

		if (hasattr (XmlNodeReader,"Read")):
		#
			f_continue_check = True

			if (self.time < 0): f_timeout_time = (time () + self.timeout_count)
			else: f_timeout_time = (self.time + self.timeout_count)

			self.parser.set ({ })

			while ((f_continue_check) and (XmlNodeReader.NodeType != XmlNodeType.Element) and (f_timeout_time > (time ()))): f_continue_check = XmlNodeReader.Read ()

			f_monoxml_dict = self.xml2array_MonoXML_walker (XmlNodeReader,strict_standard)
			XmlNodeReader.Close ()

			if (type (f_monoxml_dict) == dict): f_continue_check = self.xml2array_MonoXML_array_walker (f_monoxml_dict,strict_standard)
			if (f_continue_check): f_return = self.parser.get ()
		#

		return f_return
	#

	def xml2array_MonoXML_array_walker (self,data_dict,strict_standard = True):
	#
		"""
Imports a pre-parsed XML dictionary into the given parser instance.

:param data_dict: Result dictionary of a "xml2array_MonoXML_walker ()"
:param strict_standard: Be standard conform

:return: (bool) True on success
:since:  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_parser.xml2array_MonoXML_array_walker (data_dict,strict_standard)- (#echo(__LINE__)#)")
		f_return = False

		if (type (data_dict) == dict):
		#
			if ((len (data_dict['value']) > 0) or (len (data_dict['attributes']) > 0) or (len (data_dict['children']) > 0)):
			#
				if ((not strict_standard) and ("value" in data_dict['attributes']) and (len (data_dict['value']) < 1)):
				#
					data_dict['value'] = data_dict['attributes']['value']
					del (data_dict['attributes']['value'])
				#

				self.parser.node_add (data_dict['node_path'],data_dict['value'],data_dict['attributes'])
			#

			if (len (data_dict['children']) > 0):
			#
				for f_child_dict in data_dict['children']: self.xml2array_MonoXML_array_walker (f_child_dict,strict_standard)
			#

			f_return = True
		#

		return f_return
	#

	def xml2array_MonoXML_merged (self,XmlNodeReader):
	#
		"""
Converts XML data into a merged dictionary ... using the
"simplexml_load_string ()" result.

:param XmlNodeReader: XmlNodeReader object

:return: (dict) Merged XML tree
:since:  v0.1.00
		"""

		global _unicode_object
		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_parser.xml2array_MonoXML_merged (XmlNodeReader)- (#echo(__LINE__)#)")

		f_return = False

		if (hasattr (XmlNodeReader,"Read")):
		#
			f_node_change_check = False
			f_continue_check =True
			f_depth = 0
			f_node_path = ""
			f_node_path_list = [ ]
			f_nodes_dict = { }
			f_read_check = True

			if (self.time < 0): f_timeout_time = (time () + self.timeout_count)
			else: f_timeout_time = (self.time + self.timeout_count)

			while ((f_continue_check) and (f_timeout_time > (time ()))):
			#
				if (XmlNodeReader.NodeType == XmlNodeType.CDATA):
				#
					if (f_node_path in f_nodes_dict):
					#
						if (("xml:space" in f_nodes_dict[f_node_path]['attributes']) and (f_nodes_dict[f_node_path]['attributes']['xml:space'] == "preserve")): f_nodes_dict[f_node_path]['value'] += XmlNodeReader.Value
						else: f_nodes_dict[f_node_path]['value'] += XmlNodeReader.Value.strip ()
				#
				elif (XmlNodeReader.NodeType == XmlNodeType.Element):
				#
					f_attributes_dict = { }
					f_node_name = XmlNodeReader.Name.lower ()
					if (f_node_name.startswith ("digitstart__")): f_node_name = f_node_name[12:]

					if (XmlNodeReader.HasAttributes):
					#
						while (XmlNodeReader.MoveToNextAttribute () and (f_timeout_time > (time ()))):
						#
							f_attribute_name = XmlNodeReader.Name.lower ()
							if (type (f_attribute_name) == _unicode_object['type']): f_attribute_name = _unicode_object['str'] (f_attribute_name,"utf-8")

							if (f_attribute_name.startswith ("xmlns:")): f_attributes_dict[("xmlns:{0}".format (f_attribute_name[6:]))] = XmlNodeReader.Value
							elif (f_attribute_name == "xml:space"): f_attributes_dict['xml:space'] = XmlNodeReader.Value.lower ()
							else: f_attributes_dict[f_attribute_name] = XmlNodeReader.Value
						#

						XmlNodeReader.MoveToElement ()
					#

					f_node_path_list.append (f_node_name)
					f_node_path = "_".join (f_node_path_list)
					f_nodes_dict[f_node_path] = { "tag": f_node_name,"level": (XmlNodeReader.Depth + 1),"value": None,"attributes": f_attributes_dict }

					f_depth = XmlNodeReader.Depth
					f_continue_check = XmlNodeReader.Read ()
					f_node_change_check = True
					f_read_check = False
				#
				elif (XmlNodeReader.NodeType == XmlNodeType.EndElement):
				#
					f_continue_check = XmlNodeReader.Read ()
					f_node_change_check = True
					f_read_check = False
				#
				elif (XmlNodeReader.NodeType == XmlNodeType.Text):
				#
					if (f_node_path in f_nodes_dict):
					#
						if (("xml:space" in f_nodes_dict[f_node_path]['attributes']) and (f_nodes_dict[f_node_path]['attributes']['xml:space'] == "preserve")): f_nodes_dict[f_node_path]['value'] += XmlNodeReader.Value
						else: f_nodes_dict[f_node_path]['value'] += XmlNodeReader.Value.strip ()
					#
				#

				if (f_node_change_check):
				#
					f_node_change_check = False

					if (f_node_path in f_nodes_dict[f_node_path]):
					#
						if (("value" in f_nodes_dict[f_node_path]['attributes']) and (len (f_nodes_dict[f_node_path]['value']) < 1)):
						#
							f_nodes_dict[f_node_path]['value'] = f_nodes_dict[f_node_path]['attributes']['value']
							del (f_nodes_dict[f_node_path]['attributes']['value'])
						#

						if (f_node_path in f_return):
						#
							if ("tag" in f_return[f_node_path]):
							#
								f_node_packed_dict = f_return[f_node_path].copy ()
								f_return[f_node_path] = [ f_node_packed_dict ]
								f_node_packed_dict = None
							#

							f_return[f_node_path].append (f_nodes_dict[f_node_path])
						#
						else: f_return[f_node_path] = f_nodes_dict[f_node_path]

						del (f_nodes_dict[f_node_path])
					#

					f_depth = XmlNodeReader.Depth
					f_node_path_list.pop ()
					f_node_path = "_".join (f_node_path_list)
					f_read_check = False
				#
				elif (XmlNodeReader.Depth < f_depth):
				#
					if (f_node_path in f_nodes_dict): del (f_nodes_dict[f_node_path])

					f_depth = XmlNodeReader.Depth
					f_node_path_list.pop ()
					f_node_path = "_".join (f_node_path_list)
				#

				if (f_read_check):
				#
					if (f_continue_check): f_continue_check = XmlNodeReader.Read ()
				#
				else: f_read_check = True
			#

			XmlNodeReader.Close ()
		#

		return f_return
	#

	def xml2array_MonoXML_walker (self,XmlNodeReader,strict_standard = True,node_path = "",xml_level = 0):
	#
		"""
Converts XML data into a multi-dimensional dictionary using the recursive
algorithm.

:param XmlNodeReader: XmlNodeReader object
:param strict_standard: Be standard conform
:param node_path: Old node path (for recursive use only)
:param xml_level: Current XML depth

:return: (mixed) XML node dictionary on success; false on error
:since:  v0.1.00
		"""

		global _unicode_object
		if (type (node_path) == _unicode_object['type']): node_path = _unicode_object['str'] (node_path,"utf-8")

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_parser.xml2array_MonoXML_walker (XmlNodeReader,strict_standard,{0},{1:d})- (#echo(__LINE__)#)".format (node_path,xml_level))
		f_return = False

		if (hasattr (XmlNodeReader,"Read")):
		#
			f_attributes_dict = { }
			f_continue_check = False
			f_node_content = ""
			f_nodes_list = [ ]
			f_preserve_check = False
			f_read_check = True

			if (self.time < 0): f_timeout_time = (time () + self.timeout_count)
			else: f_timeout_time = (self.time + self.timeout_count)

			while ((not f_continue_check) and (f_read_check) and (f_timeout_time > (time ()))):
			#
				if (XmlNodeReader.NodeType == XmlNodeType.Element):
				#
					if (strict_standard):
					#
						f_node_name = XmlNodeReader.Name
						if (type (f_node_name) == _unicode_object['type']): f_node_name = _unicode_object['str'] (f_node_name,"utf-8")
					#
					else:
					#
						f_node_name = XmlNodeReader.Name.lower ()
						if (type (f_node_name) == _unicode_object['type']): f_node_name = _unicode_object['str'] (f_node_name,"utf-8")
						if (f_node_name.startswith ("digitstart__")): f_node_name = f_node_name[12:]
					#

					if (XmlNodeReader.HasAttributes):
					#
						while (XmlNodeReader.MoveToNextAttribute () and (f_timeout_time > (time ()))):
						#
							f_attribute_name = XmlNodeReader.Name.lower ()
							if (type (f_attribute_name) == _unicode_object['type']): f_attribute_name = _unicode_object['str'] (f_attribute_name,"utf-8")

							if (f_attribute_name.startswith ("xmlns:")): f_attributes_dict[("xmlns:{0}".format (f_attribute_name[6:]))] = XmlNodeReader.Value
							elif (f_attribute_name == "xml:space"): f_attributes_dict['xml:space'] = XmlNodeReader.Value.lower ()
							elif (not strict_standard): f_attributes_dict[f_attribute_name] = XmlNodeReader.Value
							else: f_attributes_dict[XmlNodeReader.Name] = XmlNodeReader.Value
						#

						XmlNodeReader.MoveToElement ()
					#

					f_continue_check = True
				#

				f_read_check = XmlNodeReader.Read ()
			#

			if (f_continue_check):
			#
				if (len (node_path) > 0): node_path = "{0} {1}".format (node_path,f_node_name)
				else: node_path = f_node_name
			#

			while ((f_continue_check) and (f_timeout_time > (time ()))):
			#
				if (xml_level < XmlNodeReader.Depth):
				#
					if (XmlNodeReader.NodeType == XmlNodeType.CDATA):
					#
						if (f_preserve_check): f_node_content += XmlNodeReader.Value
						else: f_node_content += XmlNodeReader.Value.strip ()
					#
					elif (XmlNodeReader.NodeType == XmlNodeType.Element):
					#
						f_nodes_list.append (self.xml2array_MonoXML_walker (XmlNodeReader,strict_standard,node_path,XmlNodeReader.Depth))
						f_read_check = False
					#
					elif (XmlNodeReader.NodeType == XmlNodeType.EndElement):
					#
						f_read_check = False
						XmlNodeReader.Read ()
					#
					elif (XmlNodeReader.NodeType == XmlNodeType.Text):
					#
						if (f_preserve_check): f_node_content += XmlNodeReader.Value
						else: f_node_content += XmlNodeReader.Value.strip ()
					#
					elif ((f_preserve_check) and ((XmlNodeReader.NodeType == XmlNodeType.Whitespace) or (XmlNodeReader.NodeType == XmlNodeType.SignificantWhitespace))): f_node_content += XmlNodeReader.Value

					if (f_read_check):
					#
						if (f_continue_check): f_continue_check = XmlNodeReader.Read ()
						else: XmlNodeReader.Read ()
					#
					else: f_read_check = True
				#
				else: f_continue_check = False
			#

			f_return = { "node_path": node_path,"value": f_node_content,"attributes": f_attributes_dict,"children": f_nodes_list }
		#

		return f_return
	#
#

##j## EOF