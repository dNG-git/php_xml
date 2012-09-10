/*- coding: utf-8 -*/
//j// BOF

/*n// NOTE
----------------------------------------------------------------------------
Extended Core: XML
Multiple XML parser abstraction layer
----------------------------------------------------------------------------
(C) direct Netware Group - All rights reserved
http://www.direct-netware.de/redirect.php?ext_core_xml

This Source Code Form is subject to the terms of the Mozilla Public License,
v. 2.0. If a copy of the MPL was not distributed with this file, You can
obtain one at http://mozilla.org/MPL/2.0/.
----------------------------------------------------------------------------
http://www.direct-netware.de/redirect.php?licenses;mpl2
----------------------------------------------------------------------------
NOTE_END //n*/
/**
* XML (Extensible Markup Language) is the easiest way to use a descriptive
* language for controlling applications locally and world wide.
*
* @internal   We are using javadoc to automate the documentation process for
*             creating the Developer's Manual. All sections including these
*             special comments will be removed from the release source code.
*             Use the following line to ensure 76 character sizes:
* ----------------------------------------------------------------------------
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    ext_core
* @subpackage file
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;mpl2
*             Mozilla Public License, v. 2.0
*/

package de.direct_netware;

import java.io.ByteArrayInputStream;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Iterator;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.XMLConstants;

import org.w3c.dom.Document;
import org.w3c.dom.NamedNodeMap;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

/**
* This implementation supports avax.xml.parsers.DocumentBuilder for XML
* parsing.
*
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @subpackage xml
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;mpl2
*             Mozilla Public License, v. 2.0
*/
public class directXmlParserDocumentBuilder
{
	protected DocumentBuilder DataParser;
/**
	* Debug message container
*/
	public ArrayList Debug;
/**
	* @var direct_xml_reader $parser Container for the XML document
*/
	protected directXmlReader Parser;

	public directXmlParserDocumentBuilder (directXmlReader fParser,int fTime,int fTimeoutCount,boolean fDebug)
	{
		if (fDebug) { Debug = new ArrayList (Collections.singleton ("xml_reader.directXmlReader (new)")); }

		try
		{
			DocumentBuilderFactory fDataParserFactory = DocumentBuilderFactory.newInstance ();
			fDataParserFactory.setNamespaceAware (false);
			fDataParserFactory.setValidating (false);

			try
			{
				fDataParserFactory.setXIncludeAware (false);
				fDataParserFactory.setFeature (XMLConstants.FEATURE_SECURE_PROCESSING,true);
			}
			catch (Throwable fHandledException)
			{
				try { fDataParserFactory.setAttribute ("http://javax.xml.XMLConstants/feature/secure-processing",Boolean.TRUE); }
				catch (Throwable fUnhandledException) { }
			}

			DataParser = fDataParserFactory.newDocumentBuilder ();
		}
		catch (Throwable fHandledException) { DataParser = null; }

		Parser = fParser;
	}

	protected directArray xml2array (String fData,String fNodePath,boolean fStrictStandard)
	{
		directArray fReturn = null;

		try
		{
			Document fDocument = null;

			if ((DataParser != null)&&(fData != null))
			{
				ByteArrayInputStream fBytestream = new ByteArrayInputStream (fData.getBytes ());
				fDocument = DataParser.parse (fBytestream);
			}

			if (fDocument != null) { fReturn = xml2arrayNode (null,fDocument,fNodePath,fStrictStandard); }
		}
		catch (Throwable fHandledException) { fHandledException.printStackTrace(); fReturn = null; }

		return fReturn;
	}

	public directArray xml2arrayDocument (String fData,boolean fStrictStandard)
	{
		directArray fXmlArray = xml2array (fData,null,fStrictStandard);

		if (fXmlArray != null)
		{
			Parser.set (new directArray ());
			xml2arrayDocumentArrayWalker (fXmlArray,"");
		}

		return Parser.get ();
	}

	protected void xml2arrayDocumentArrayWalker (directArray fXmlArray,String fNodePath)
	{
		String fNodeName = fNodePath;
		directArray fXmlNodeArray = null;

		if (fXmlArray.keyExists ("xml.mtree")) { fXmlArray.keyRemove ("xml.mtree"); }
		else if (fXmlArray.keyExists ("tag"))
		{
			fXmlNodeArray = fXmlArray;
			fXmlArray = null;
		}
		else if (fXmlArray.keyExists ("xml.item"))
		{
			fXmlNodeArray = fXmlArray.valueGetArray ("xml.item");
			fXmlArray.keyRemove ("xml.item");
		}

		if (fXmlNodeArray != null)
		{
			directArray fAttributes = ((fXmlNodeArray.keyExists ("attributes"))&&(fXmlNodeArray.valueGetArray("attributes").size () > 0) ? fXmlNodeArray.valueGetArray("attributes") : null);
			fNodeName += ((fNodePath.length () > 0) ? (" " + fXmlNodeArray.valueGet ("tag")) : fXmlNodeArray.valueGet ("tag"));

			Parser.nodeAdd (fNodeName,((String)fXmlNodeArray.valueGet ("value")),fAttributes);
		}

		if ((fXmlArray != null)&&(fXmlArray.size () > 0))
		{
			for (Iterator fIterator = fXmlArray.values().iterator ();fIterator.hasNext ();)
			{
				fXmlNodeArray = (directArray)fIterator.next ();
				xml2arrayDocumentArrayWalker (fXmlNodeArray,fNodeName);
			}
		}
	}

	public directArray xml2arrayDocumentMerged (String fData) { return xml2array (fData,"",false); }

	protected directArray xml2arrayNode (directArray fParent,Node fNode,String fNodePath,boolean fStrictStandard)
	{
		directArray fReturn = fParent;

		if (fNode != null)
		{
			boolean fContinueCheck = false;

			switch (fNode.getNodeType ())
			{
			case Node.ATTRIBUTE_NODE:
			{
				if (fParent.keyExists ("attributes")) { fParent = fReturn.valueGetArray ("attributes"); }
				else
				{
					fParent = new directArray ();
					fReturn.keyAdd ("attributes",fParent);
				}

				if (fNode.getNodeName().toLowerCase () == "xml:space") { fParent.keyAdd ((fNode.getNodeName().toLowerCase ()),(fNode.getNodeValue().toLowerCase ())); }
				else { fParent.keyAdd ((fNode.getNodeName().toLowerCase ()),(fNode.getNodeValue ())); }

				break;
			}
			case Node.CDATA_SECTION_NODE:
			{
				fContinueCheck = true;
				break;
			}
			case Node.DOCUMENT_NODE:
			{
				fParent = new directArray ();
				fReturn = xml2arrayWalker (fParent,(fNode.getChildNodes ()),fNodePath,fStrictStandard);

				break;
			}
			case Node.ELEMENT_NODE:
			{
				if ((fNodePath == null)&&(!fParent.keyExists ("xml.item"))&&(fParent.keyExists ("tag")))
				{
					fParent = new directArray ();
					fParent.keyAdd ("xml.item",fReturn);
					fReturn = xml2arrayNode (fParent,fNode,fNodePath,fStrictStandard);
				}
				else
				{
					String fName;

					if (fStrictStandard) { fName = fNode.getNodeName (); }
					else
					{
						fName = fNode.getNodeName().toLowerCase ();
						if (fName.startsWith ("digitstart__")) { fName = fName.substring (12); }
					}

					directArray fNodeArray = new directArray ();
					fNodeArray.keyAdd ("tag",fName);
					fNodeArray.keyAdd ("value","");

					if (fNode.hasAttributes ()) { fNodeArray = xml2arrayWalker (fNodeArray,(fNode.getAttributes ()),null,fStrictStandard); }

					if (fNodePath == null)
					{
						if (fNode.hasChildNodes ()) { fNodeArray = xml2arrayWalker (fNodeArray,(fNode.getChildNodes ()),null,fStrictStandard); }

						if (fNodeArray != null)
						{
							if (fParent.keyExists (fName))
							{
								fParent = fReturn.valueGetArray (fName);

								if (fParent.keyExists ("xml.mtree"))
								{
									fParent.valueSet ("xml.mtree",(fParent.size () - 1));
									fParent.keyAdd ((fParent.size () - 1),fNodeArray);
								}
								else
								{
									directArray fWrappedParent = new directArray ();
									fWrappedParent.keyAdd ("xml.mtree",1);
									fWrappedParent.keyAdd (0,fParent);
									fWrappedParent.keyAdd (1,fNodeArray);
									fReturn.valueSet (fName,fWrappedParent);
								}
							}
							else { fReturn.keyAdd (fName,fNodeArray); }
						}
					}
					else
					{
						if (!fNodePath.equals ("")) { fName = (fNodePath + "_" + fName); }

						if (fReturn.keyExists (fName))
						{
							fParent = fReturn.valueGetArray (fName);

							if (fParent.keyExists ("tag"))
							{
								directArray fWrappedParent = new directArray ();
								fWrappedParent.keyAdd (0,fParent);
								fWrappedParent.keyAdd (1,fNodeArray);
								fReturn.valueSet (fName,fWrappedParent);
							}
							else { fParent.keyAdd ((fParent.size ()),fNodeArray); }
						}
						else { fReturn.keyAdd (fName,fNodeArray); }

						if (fNode.hasChildNodes ()) { fReturn = xml2arrayWalker (fReturn,(fNode.getChildNodes ()),fName,fStrictStandard); }
					}

					if (!fStrictStandard)
					{
						fParent = fReturn.valueGetArray (fName);

						if (fParent.keyExists ("value"))
						{
							String fValue = (String)fParent.valueGet ("value");

							if (fValue.length () == 0)
							{
								fParent.valueSet ("value",((String)fParent.valueGet ("attributes value")));
								fParent.keyRemove ("attributes value",true);
							}
						}
						else if (fReturn.keyExists (fName + " " + (fParent.size () - 2) + " value"))
						{
							int fParentPosition = (fParent.size () - 2);
							String f_value = (String)fParent.valueGet (fParentPosition + " value");

							if (f_value.length () == 0)
							{
								fParent.valueSet (fParentPosition + " value",((String)fParent.valueGet (fParentPosition + " attributes value")));
								fParent.keyRemove (fParentPosition + " attributes value",true);
							}
						}
					}
				}

				break;
			}
			case Node.TEXT_NODE:
			{
				fContinueCheck = true;
				break;
			}
			}

			if (fContinueCheck)
			{
				boolean fCDataCheck = ((fNodePath == null) ? true : fParent.keyExists (fNodePath));

				String fNodeValue = "";
				String fValue = "";

				if (fCDataCheck)
				{
					if (fNodePath != null) { fParent = fReturn.valueGetArray (fNodePath); }
					else if (fParent.keyExists ("xml.item")) { fParent = fParent.valueGetArray ("xml.item"); }
				}

				if (fParent != null) { fCDataCheck = fParent.keyExists ("attributes xml:space"); }

				if (fCDataCheck) { fNodeValue = (String)fParent.valueGet ("attributes xml:space"); }
				else { fCDataCheck = fParent.keyExists ("value"); }

				if (fNodeValue.equals ("preserve"))
				{
					fNodeValue = fNode.getNodeValue ();

					if (fNodeValue != null)
					{
						fValue = (String)fParent.valueGet ("value");
						fValue += fNodeValue;
						fParent.valueSet ("value",fValue);
					}
				}
				else
				{
					fNodeValue = fNode.getNodeValue ();
					if (fNodeValue == null) { fCDataCheck = false; }

					if (fCDataCheck)
					{
						fNodeValue = fNodeValue.trim ();
						if (fNodeValue.length () < 1) { fCDataCheck = false; }
					}

					if (fCDataCheck)
					{
						fValue = (String)fParent.valueGet ("value");
						fValue += fNodeValue;
						fParent.valueSet ("value",fValue);
					}
				}
			}
		}

		return fReturn;
	}

	protected directArray xml2arrayWalker (directArray fParent,NamedNodeMap fNodes,String fNodePath,boolean fStrictStandard)
	{
		directArray fReturn = fParent;
		int fNodeCount = ((fNodes == null) ? 0 : fNodes.getLength ());

		if (fNodeCount > 0)
		{
			for (int fI = 0;fI < fNodeCount;fI++) { fReturn = xml2arrayNode (fReturn,(fNodes.item (fI)),fNodePath,fStrictStandard); }
		}

		return fReturn;
	}

	protected directArray xml2arrayWalker (directArray fParent,NodeList fNodes,String fNodePath,boolean fStrictStandard)
	{
		directArray fReturn = fParent;
		int fNodeCount = ((fNodes == null) ? 0 : fNodes.getLength ());

		if (fNodeCount > 0)
		{
			for (int fI = 0;fI < fNodeCount;fI++) { fReturn = xml2arrayNode (fReturn,(fNodes.item (fI)),fNodePath,fStrictStandard); }
		}

		return fReturn;
	}
}

//j// EOF