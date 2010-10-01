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

from System.Xml import XmlNodeType
import time

class direct_xml_parser_MonoXML (object):
#
	"""
This implementation supports expat for XML parsing.

@author     direct Netware Group
@copyright  (C) direct Netware Group - All rights reserved
@package    ext_core
@subpackage xml
@since      v0.1.00
@license    http://www.direct-netware.de/redirect.php?licenses;w3c
            W3C (R) Software License
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

	def __init__ (self,f_parser,f_time = -1,f_timeout_count = 5,f_debug = False):
	#
		"""
Constructor __init__ (direct_xml_parser_MonoXML)

@param f_parser Container for the XML document
@param f_time Current UNIX timestamp
@param f_timeout_count Retries before timing out
@param f_debug Debug flag
@since v0.1.00
		"""

		if (f_debug): self.debug = [ "xml/#echo(__FILEPATH__)# -xml_parser->__init__ (direct_xml_parser_MonoXML)- (#echo(__LINE__)#)" ]
		else: self.debug = None

		"""
----------------------------------------------------------------------------
Connect to the Python container for the XML document
----------------------------------------------------------------------------
		"""

		self.parser = f_parser

		if (f_time < 0): self.time = time.time ()
		else: self.time = f_time

		if (f_timeout_count == None): self.timeout_count = 5
		else: self.timeout_count = f_timeout_count
	#

	def __del__ (self):
	#
		"""
Destructor __del__ (direct_xml_parser_MonoXML)

@since v0.1.00
		"""

		self.del_direct_xml_parser_MonoXML ()
	#

	def del_direct_xml_parser_MonoXML (self):
	#
		"""
Destructor del_direct_xml_parser_MonoXML (direct_xml_parser_MonoXML)

@since v0.1.00
		"""

		self.parser = None
	#

	def xml2array_MonoXML (self,f_xmlreader,f_strict_standard = True):
	#
		"""
Adds the result of an expat parsing operation to the defined XML instance if
the parser completed its work.

@param  f_xmlreader XmlNodeReader object
@param  f_strict_standard Be standard conform
@return (array) Multi-dimensional XML tree
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_parser->xml2array_MonoXML (+f_xnkreader,+f_strict_standard)- (#echo(__LINE__)#)")
		f_return = { }

		if (hasattr (f_xmlreader,"Read")):
		#
			f_continue_check = True
			f_timeout_time = (self.time + self.timeout_count)
			self.parser.set ({ })

			while ((f_continue_check) and (f_xmlreader.NodeType != XmlNodeType.Element) and (f_timeout_time > (time.time ()))): f_continue_check = f_xmlreader.Read ()

			f_xmlreader_array = self.xml2array_XMLReader_walker (f_xmlreader,f_strict_standard)
			f_xmlreader.Close ()

			if (type (f_xmlreader_array) == dict): f_continue_check = self.xml2array_XMLReader_array_walker (f_xmlreader_array,f_strict_standard)
			if (f_continue_check): f_return = self.parser.get ()
		#

		return f_return
	#

	def xml2array_XMLReader_array_walker (self,f_data,f_strict_standard = True):
	#
		"""
Imports a pre-parsed XML array into the given parser instance.

@param  f_data Result array of a "xml2array_XMLReader_walker ()"
@param  f_strict_standard Be standard conform
@return (boolean) True on success
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_parser->xml2array_XMLReader_array_walker (+f_data,+f_strict_standard)- (#echo(__LINE__)#)")
		f_return = False

		if (type (f_data) == dict):
		#
			if ((len (f_data['value']) > 0) or (len (f_data['attributes']) > 0) or (len (f_data['children']) > 0)):
			#
				if ((not f_strict_standard) and ("value" in f_data['attributes']) and (len (f_data['value']) < 1)):
				#
					f_data['value'] = f_data['attributes']['value']
					del (f_data['attributes']['value'])
				#

				self.parser.node_add (f_data['node_path'],f_data['value'],f_data['attributes'])
			#

			if (len (f_data['children']) > 0):
			#
				for f_child_array in f_data['children']: self.xml2array_XMLReader_array_walker (f_child_array,f_strict_standard)
			#

			f_return = True
		#

		return f_return
	#

	def xml2array_XMLReader_merged (self,f_xmlreader):
	#
		"""
Converts XML data into a merged array ... using the
"simplexml_load_string ()" result.

@param  f_xmlreader SimpleXMLElement object
@return (array) Merged XML tree
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_parser->xml2array_XMLReader_merged (+f_xmlreader)- (#echo(__LINE__)#)")
		f_return = False

		if (hasattr (f_xmlreader,"Read")):
		#
			f_node_change_check = False
			f_continue_check =True
			f_depth = 0
			f_node_path = ""
			f_node_path_array = [ ]
			f_nodes_array = { }
			f_read_check = True
			f_timeout_time = (self.time + self.timeout_count)

			while ((f_continue_check) and (f_timeout_time > (time.time ()))):
			#
				if (f_xmlreader.NodeType == XmlNodeType.CDATA):
				#
					if (f_node_path in f_nodes_array):
					#
						if (("xml:space" in f_nodes_array[f_node_path]['attributes']) and (f_nodes_array[f_node_path]['attributes']['xml:space'] == "preserve")): f_nodes_array[f_node_path]['value'] += f_xmlreader.Value
						else: f_nodes_array[f_node_path]['value'] += f_xmlreader.Value.strip ()
				#
				elif (f_xmlreader.NodeType == XmlNodeType.Element):
				#
					f_attributes_array = { }
					f_node_name = f_xmlreader.Name.lower ()
					if (f_node_name.startswith ("digitstart__")): f_node_name = f_node_name[14:]

					if (f_xmlreader.HasAttributes):
					#
						while (f_xmlreader.MoveToNextAttribute () and (f_timeout_time > (time.time ()))):
						#
							f_attribute_name = f_xmlreader.Name.lower ()

							if (f_attribute_name.startswith ("xmlns:")): f_attributes_array["xmlns:%s" % f_attribute_name[6:]] = f_xmlreader.Value
							elif (f_attribute_name == "xml:space"): f_attributes_array['xml:space'] = f_xmlreader.Value.lower ()
							else: f_attributes_array[f_attribute_name] = f_xmlreader.Value
						#

						f_xmlreader.MoveToElement ()
					#

					f_node_path_array.append (f_node_name)
					f_node_path = "_".join (f_node_path_array)
					f_nodes_array[f_node_path] = { "tag": f_node_name,"level": (f_xmlreader.Depth + 1),"value": None,"attributes": f_attributes_array }

					f_depth = f_xmlreader.Depth
					f_continue_check = f_xmlreader.Read ()
					f_node_change_check = True
					f_read_check = False
				#
				elif (f_xmlreader.NodeType == XmlNodeType.EndElement):
				#
					f_continue_check = f_xmlreader.Read ()
					f_node_change_check = True
					f_read_check = False
				#
				elif (f_xmlreader.NodeType == XmlNodeType.Text):
				#
					if (f_node_path in f_nodes_array):
					#
						if (("xml:space" in f_nodes_array[f_node_path]['attributes']) and (f_nodes_array[f_node_path]['attributes']['xml:space'] == "preserve")): f_nodes_array[f_node_path]['value'] += f_xmlreader.Value
						else: f_nodes_array[f_node_path]['value'] += f_xmlreader.Value.strip ()
					#
				#

				if (f_node_change_check):
				#
					f_node_change_check = False

					if (f_node_path in f_nodes_array[f_node_path]):
					#
						if (("value" in f_nodes_array[f_node_path]['attributes']) and (len (f_nodes_array[f_node_path]['value']) < 1)):
						#
							f_nodes_array[f_node_path]['value'] = f_nodes_array[f_node_path]['attributes']['value']
							del (f_nodes_array[f_node_path]['attributes']['value'])
						#

						if (f_node_path in f_return):
						#
							if ("tag" in f_return[f_node_path]):
							#
								f_node_packed_array = f_return[f_node_path].copy ()
								f_return[f_node_path] = [ f_node_packed_array ]
								f_node_packed_array = None
							#

							f_return[f_node_path].append (f_nodes_array[f_node_path])
						#
						else: f_return[f_node_path] = f_nodes_array[f_node_path]

						del (f_nodes_array[f_node_path])
					#

					f_depth = f_xmlreader.Depth
					f_node_path_array.pop ()
					f_node_path = "_".join (f_node_path_array)
					f_read_check = False
				#
				elif (f_xmlreader.Depth < f_depth):
				#
					if (f_node_path in f_nodes_array): del (f_nodes_array[f_node_path])

					f_depth = f_xmlreader.Depth
					f_node_path_array.pop ()
					f_node_path = "_".join (f_node_path_array)
				#

				if (f_read_check):
				#
					if (f_continue_check): f_continue_check = f_xmlreader.Read ()
				#
				else: f_read_check = True
			#

			f_xmlreader.Close ()
		#

		return f_return
	#

	def xml2array_XMLReader_walker (self,f_xmlreader,f_strict_standard = True,f_node_path = "",f_xml_level = 0):
	#
		"""
Converts XML data into a multi-dimensional array using the recursive
algorithm.

@param  f_xmlreader XmlNodeReader object
@param  f_strict_standard Be standard conform
@param  f_node_path Old node path (for recursive use only)
@param  f_xml_level Current XML depth
@return (mixed) XML node array on success; false on error
@since  v0.1.00
		"""

		if (self.debug != None): self.debug.append ("xml/#echo(__FILEPATH__)# -xml_parser->xml2array_XMLReader_walker (+f_xmlreader,+f_strict_standard,%s,%i)- (#echo(__LINE__)#)" % ( f_node_path,f_xml_level ))
		f_return = False

		if (hasattr (f_xmlreader,"Read")):
		#
			f_attributes_array = { }
			f_continue_check = False
			f_node_content = ""
			f_nodes_array = [ ]
			f_preserve_check = False
			f_read_check = True
			f_timeout_time = (self.time + self.timeout_count)

			while ((not f_continue_check) and (f_read_check) and (f_timeout_time > (time.time ()))):
			#
				if (f_xmlreader.NodeType == XmlNodeType.Element):
				#
					if (f_strict_standard): f_node_name = f_xmlreader.Name
					else:
					#
						f_node_name = f_xmlreader.Name.lower ()
						if (f_node_name.startswith ("digitstart__")): f_node_name = f_node_name[14:]
					#

					if (f_xmlreader.HasAttributes):
					#
						while (f_xmlreader.MoveToNextAttribute () and (f_timeout_time > (time.time ()))):
						#
							f_attribute_name = f_xmlreader.Name.lower ()

							if (f_attribute_name.startswith ("xmlns:")): f_attributes_array["xmlns:%s" % f_attribute_name[6:]] = f_xmlreader.Value
							elif (f_attribute_name == "xml:space"): f_attributes_array['xml:space'] = f_xmlreader.Value.lower ()
							elif (not f_strict_standard): f_attributes_array[f_attribute_name] = f_xmlreader.Value
							else: f_attributes_array[f_xmlreader.Name] = f_xmlreader.Value
						#

						f_xmlreader.MoveToElement ()
					#

					f_continue_check = True
				#

				f_read_check = f_xmlreader.Read ()
			#

			if (f_continue_check):
			#
				if (len (f_node_path) > 0): f_node_path = "%s %s" % ( f_node_path,f_node_name )
				else: f_node_path = f_node_name
			#

			while ((f_continue_check) and (f_timeout_time > (time.time ()))):
			#
				if (f_xml_level < f_xmlreader.Depth):
				#
					if (f_xmlreader.NodeType == XmlNodeType.CDATA):
					#
						if (f_preserve_check): f_node_content += f_xmlreader.Value
						else: f_node_content += f_xmlreader.Value.strip ()
					#
					elif (f_xmlreader.NodeType == XmlNodeType.Element):
					#
						f_nodes_array.append (self.xml2array_XMLReader_walker (f_xmlreader,f_strict_standard,f_node_path,f_xmlreader.Depth))
						f_read_check = False
					#
					elif (f_xmlreader.NodeType == XmlNodeType.EndElement):
					#
						f_read_check = False
						f_xmlreader.Read ()
					#
					elif (f_xmlreader.NodeType == XmlNodeType.Text):
					#
						if (f_preserve_check): f_node_content += f_xmlreader.Value
						else: f_node_content += f_xmlreader.Value.strip ()
					#
					elif ((f_preserve_check) and ((f_xmlreader.NodeType == XmlNodeType.Whitespace) or (f_xmlreader.NodeType == XmlNodeType.SignificantWhitespace))): f_node_content += f_xmlreader.Value

					if (f_read_check):
					#
						if (f_continue_check): f_continue_check = f_xmlreader.Read ()
						else: f_xmlreader.Read ()
					#
					else: f_read_check = True
				#
				else: f_continue_check = False
			#

			f_return = { "node_path": f_node_path,"value": f_node_content,"attributes": f_attributes_array,"children": f_nodes_array }
		#

		return f_return
	#
#

##j## EOF