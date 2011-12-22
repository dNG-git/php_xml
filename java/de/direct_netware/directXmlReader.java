/*- coding: utf-8 -*/
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
* @subpackage xml
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;w3c
*             W3C (R) Software License
*/

package de.direct_netware;

import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.Date;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

/**
* This class provides a bridge between Java and XML to read XML on the fly.
*
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    ext_core
* @subpackage xml
* @since      v1.0.0
* @license    http://www.direct-netware.de/redirect.php?licenses;w3c
*             W3C (R) Software License
*/
public class directXmlReader
{
/**
	* XML data
*/
	protected directArray Data;
/**
	* Debug message container
*/
	public ArrayList Debug;
/**
	* Path of the cached node pointer
*/
	protected String DataCacheNode;
/**
	* Reference of the cached node pointer (string if unset)
*/
	protected directArray DataCachePointer;
/**
	* Charset used
*/
	protected String DataCharset;
/**
	* Cache for known XML NS (URI)
*/
	protected directArray DataNs;
/**
	* Cache for the compact number of a XML NS
*/
	protected directArray DataNsCompact;
/**
	* Counter for the compact link numbering
*/
	protected int DataNsCounter;
/**
	* Cache for the XML NS and the corresponding number
*/
	protected directArray DataNsDefault;
/**
	* Cache of node pathes with a predefined NS (key = Compact name)
*/
	protected directArray DataNsPredefinedCompact;
/**
	* Cache of node pathes with a predefined NS (key = Full name)
*/
	protected directArray DataNsPredefinedDefault;
/**
	* Parse data only
*/
	protected boolean DataParseOnly;
/**
	* The selected parser implementation
*/
	protected directXmlParserDocumentBuilder DataParser;
/**
	* Current UNIX timestamp
*/
	protected Date Time;
/**
	* Retries before timing out
*/
	protected int TimeoutCount;

/* -------------------------------------------------------------------------
Construct the class
------------------------------------------------------------------------- */

/**
	* Constructor directXmlReader ("UTF-8",true,-1,5,false)
	*
	* @since v0.1.00
*/
	public directXmlReader () { this ("UTF-8",true,-1,5,false); }
/**
	* Constructor directXmlReader (fCharset,true,-1,5,false)
	*
	* @param fCharset Charset to be added as information to XML output
	* @since v0.1.00
*/
	public directXmlReader (String fCharset) { this (fCharset,true,-1,5,false); }
/**
	* Constructor directXmlReader (fCharset,fParseOnly,-1,5,false)
	*
	* @param fCharset Charset to be added as information to XML output
	* @param fParseOnly Parse data only
	* @since v0.1.00
*/
	public directXmlReader (String fCharset,boolean fParseOnly) { this (fCharset,fParseOnly,-1,5,false); }
/**
	* Constructor directXmlReader (fCharset,fParseOnly,fTime,5,false)
	*
	* @param fCharset Charset to be added as information to XML output
	* @param fParseOnly Parse data only
	* @param fTime Current UNIX timestamp
	* @since v0.1.00
*/
	public directXmlReader (String fCharset,boolean fParseOnly,int fTime) { this (fCharset,fParseOnly,fTime,5,false); }
/**
	* Constructor directXmlReader (fCharset,fParseOnly,fTime,fTimeoutCount,false)
	*
	* @param fCharset Charset to be added as information to XML output
	* @param fParseOnly Parse data only
	* @param fTime Current UNIX timestamp
	* @param fTimeoutCount Retries before timing out
	* @since v0.1.00
*/
	public directXmlReader (String fCharset,boolean fParseOnly,int fTime,int fTimeoutCount) { this (fCharset,fParseOnly,fTime,fTimeoutCount,false); }
/**
	* Constructor directXmlReader (fCharset,fParseOnly,fTime,fTimeoutCount,fDebug)
	*
	* @param fCharset Charset to be added as information to XML output
	* @param fParseOnly Parse data only
	* @param fTime Current UNIX timestamp
	* @param fTimeoutCount Retries before timing out
	* @param fDebug Debug flag
	* @since v0.1.00
*/
	public directXmlReader (String fCharset,boolean fParseOnly,int fTime,int fTimeoutCount,boolean fDebug)
	{
		if (fDebug) { Debug = new ArrayList (Collections.singleton ("xml_reader.directXmlReader (new)")); }
		DataParser = new directXmlParserDocumentBuilder (this,fTime,fTimeoutCount,fDebug);

/* -------------------------------------------------------------------------
Initiate the array tree cache
------------------------------------------------------------------------- */

		DataCacheNode = "";
		DataCharset = fCharset.toUpperCase ();
		DataNs = new directArray ();
		DataNsCompact = new directArray ();
		DataNsCounter = 0;
		DataNsDefault = new directArray ();
		DataNsPredefinedCompact = new directArray ();
		DataNsPredefinedDefault = new directArray ();
		DataParseOnly = fParseOnly;
	}

/**
	* Builds recursively a valid XML ouput reflecting the given XML array tree.
	*
	* @param  fXmlArray XML array tree level to work on
	* @return XML output string
	* @since  v0.1.00
*/
	public String array2xml (directArray fXmlArray) { return array2xml (fXmlArray,true); }
/**
	* Builds recursively a valid XML ouput reflecting the given XML array tree.
	*
	* @param  fXmlArray XML array tree level to work on
	* @param  fStrictStandard Be standard conform
	* @return XML output string
	* @since  v0.1.00
*/
	public String array2xml (directArray fXmlArray,boolean fStrictStandard)
	{
		if (Debug != null) { Debug.add ("xml_reader.array2xml (fXmlArray,fStrictStandard)"); }
		String fReturn = "";

		if (fXmlArray.size () > 0)
		{
			Pattern fReTagDigit = Pattern.compile ("\\d");
			directArray fXmlNodeArray;
			String fXmlNodeTag;

			for (Iterator fIterator = fXmlArray.values().iterator ();fIterator.hasNext ();)
			{
				fXmlNodeArray = (directArray)fIterator.next ();

				if (fXmlNodeArray.keyExists ("xml.mtree"))
				{
					fXmlNodeArray.keyRemove ("xml.mtree");
					fReturn += array2xml (fXmlNodeArray,fStrictStandard);
				}
				else if (fXmlNodeArray.keyExists ("xml.item"))
				{
					if (Debug != null) { fReturn += "\n"; }
					fReturn += array2xmlItemEncoder (fXmlNodeArray.valueGetArray ("xml.item"),false,fStrictStandard);
					if (Debug != null) { fReturn += "\n"; }

					fXmlNodeTag = (String)fXmlNodeArray.valueGetArray("xml.item").valueGet ("tag");
					if (fReTagDigit.matcher(fXmlNodeTag).matches ()) { fXmlNodeTag = ("digitstart__" + fXmlNodeTag); }

					fXmlNodeArray.keyRemove ("xml.item");
					fReturn += array2xml (fXmlNodeArray,fStrictStandard);

					if (Debug != null) { fReturn += "\n"; }
					fReturn += "</" + fXmlNodeTag + ">";
				}
				else
				{
					fXmlNodeTag = (String)fXmlNodeArray.valueGet ("tag");

					if (fXmlNodeTag.length () > 0)
					{
						if (Debug != null) { fReturn += "\n"; }
						fReturn += array2xmlItemEncoder (fXmlNodeArray,true,fStrictStandard);
					}
				}
			}
		}

		return fReturn.trim ();
	}

/**
	* Builds recursively a valid XML ouput reflecting the given XML array tree.
	*
	* @param  fData Array containing information about the current item
	* @return XML output string
	* @since  v0.1.00
*/
	public String array2xmlItemEncoder (directArray fData) { return array2xmlItemEncoder (fData,true,true); }
/**
	* Builds recursively a valid XML ouput reflecting the given XML array tree.
	*
	* @param  fData Array containing information about the current item
	* @param  fCloseTag Output will contain a ending tag if true
	* @return XML output string
	* @since  v0.1.00
*/
	public String array2xmlItemEncoder (directArray fData,boolean fCloseTag) { return array2xmlItemEncoder (fData,fCloseTag,true); }
/**
	* Builds recursively a valid XML ouput reflecting the given XML array tree.
	*
	* @param  fData Array containing information about the current item
	* @param  fCloseTag Output will contain a ending tag if true
	* @param  fStrictStandard Be standard conform
	* @return XML output string
	* @since  v0.1.00
*/
	public String array2xmlItemEncoder (directArray fData,boolean fCloseTag,boolean fStrictStandard)
	{
		if (Debug != null) { Debug.add ("xml_reader.array2xmlItemEncoder (fData,fCloseTag,fStrictStandard)"); }
		String fReturn = "";

		boolean fValueAttributeCheck = (fStrictStandard ? false : true);
		String fTag = (String)fData.valueGet ("tag");
		String fValue = (fData.keyExists ("value") ? (String)fData.valueGet ("value") : null);
		Matcher fReResultObject = Pattern.compile("\\d").matcher (fTag);

		if (fTag.length () > 0)
		{
			if (fReResultObject.matches ()) { fTag = ("digitstart__" + fTag); }
			fReturn += "<" + fTag;

			if (fData.keyExists ("attributes"))
			{
				directArray fAttributes = fData.valueGetArray ("attributes");
				String fIteratorKey;
				String fIteratorValue;

				for (Iterator fIterator = fAttributes.keySet().iterator ();fIterator.hasNext ();)
				{
					fIteratorKey = (String)fIterator.next ();
					fIteratorValue = (String)fAttributes.valueGet (fIteratorKey);

					if ((!fStrictStandard)&&(fIteratorKey.equals ("value"))&&((fValue == null)||(fValue.length () < 1))) { fValue = fIteratorValue; }
					else
					{
						fIteratorValue = fIteratorValue.replaceAll("&","&amp;").replaceAll("<","&lt;").replaceAll(">","&gt;").replaceAll ("\"","&quot;");

						try
						{
							if (DataCharset != "UTF-8") { fIteratorValue = new String (fIteratorValue.getBytes (),DataCharset); }
						}
						catch (UnsupportedEncodingException fUnhandledException) { }

						fReturn += " " + fIteratorKey + "=\"" + fIteratorValue + "\"";
					}
				}
			}

			if ((fValue != null)&&((fStrictStandard)||(fValue.length () > 0)))
			{
				if (fValueAttributeCheck)
				{
					if (fValue.contains ("&")) { fValueAttributeCheck = false; }
					else if (fValue.contains ("<")) { fValueAttributeCheck = false; }
					else if (fValue.contains (">")) { fValueAttributeCheck = false; }
					else if (fValue.contains ("\"")) { fValueAttributeCheck = false; }
					else if (Pattern.compile("\\s").matcher(fValue.replaceAll (" ","_")).matches ()) { fValueAttributeCheck = false; }
				}

				if (fValueAttributeCheck)
				{
					try
					{
						if (DataCharset != "UTF-8") { fValue = new String (fValue.getBytes (),DataCharset); }
					}
					catch (UnsupportedEncodingException fUnhandledException) { }

					fReturn += " value=\"" + fValue + "\"";
				}
			}

			if ((fValueAttributeCheck)&&(fCloseTag)) { fReturn += " />"; }
			else
			{
				fReturn += ">";

				if ((fValue != null)&&(!fValueAttributeCheck))
				{
					try
					{
						if (DataCharset != "UTF-8") { fValue = new String (fValue.getBytes (),DataCharset); }
					}
					catch (UnsupportedEncodingException fUnhandledException) { }

					if ((!fValue.contains ("<"))&&(!fValue.contains (">")))
					{
						fValue = fValue.replaceAll ("&","&amp;");
						fReturn += fValue;
					}
					else
					{
						if (fValue.contains ("<")) { fValue = fValue.replaceAll ("]]>","]]]]><![CDATA[>"); }
						fReturn += "<![CDATA[" + fValue + "]]>";
					}
				}
			}

			if ((!fValueAttributeCheck)&&(fCloseTag)) { fReturn += "</" + fTag + ">"; }
		}

		return fReturn;
	}

/**
	* Changes the object behaviour of deleting cached data after parsing is
	* completed.
	*
	* @return Accepted state
	* @since  v0.1.00
*/
	public boolean defineParseOnly () { return defineParseOnly (true); }
/**
	* Changes the object behaviour of deleting cached data after parsing is
	* completed.
	*
	* @param  fParseOnly Parse data only
	* @return Accepted state
	* @since  v0.1.00
*/
	public boolean defineParseOnly (boolean fParseOnly)
	{
		if (Debug != null) { Debug.add ("xml_reader.defineParseOnly (fParseOnly)"); }

		DataParseOnly = fParseOnly;
		return DataParseOnly;
	}

/**
	* This operation just gives back the content of self.data.
	*
	* @return (mixed) XML data on success; false on error
	* @since  v0.1.00
*/
	public synchronized directArray get ()
	{
		if (Debug != null) { Debug.add ("xml_reader.get ()"); }
		return Data;
	}

/**
	* Adds a XML node with content - recursively if required.
	*
	* @param  fNodePath Path to the new node - delimiter is space
	* @return False on error
	* @since  v0.1.00
*/
	public synchronized boolean nodeAdd (String fNodePath) { return nodeAdd (fNodePath,"",null,true); }
/**
	* Adds a XML node with content - recursively if required.
	*
	* @param  fNodePath Path to the new node - delimiter is space
	* @param  fValue Value for the new node
	* @return False on error
	* @since  v0.1.00
*/
	public synchronized boolean nodeAdd (String fNodePath,String fValue) { return nodeAdd (fNodePath,fValue,null,true); }
/**
	* Adds a XML node with content - recursively if required.
	*
	* @param  fNodePath Path to the new node - delimiter is space
	* @param  fValue Value for the new node
	* @param  fAttributes Attributes of the node
	* @return False on error
	* @since  v0.1.00
*/
	public synchronized boolean nodeAdd (String fNodePath,String fValue,directArray fAttributes) { return nodeAdd (fNodePath,fValue,fAttributes,true); }
/**
	* Adds a XML node with content - recursively if required.
	*
	* @param  fNodePath Path to the new node - delimiter is space
	* @param  fValue Value for the new node
	* @param  fAttributes Attributes of the node
	* @param  fAddRecursively True to create the required tree recursively
	* @return False on error
	* @since  v0.1.00
*/
	public synchronized boolean nodeAdd (String fNodePath,String fValue,directArray fAttributes,boolean fAddRecursively)
	{
		if (Debug != null) { Debug.add ("xml_reader.nodeAdd (" + fNodePath + ",fValue,fAttributes,fAddRecursively)"); }
		boolean fReturn = false;

		fNodePath = nsTranslatePath (fNodePath);
		String fNodePathDone = "";
		directArray fNodePointer = Data;

		if ((DataCacheNode.length () > 0)&&(Pattern.compile ("^" + (Pattern.quote (fNodePath)),Pattern.CASE_INSENSITIVE).matcher(DataCacheNode).matches ()))
		{
			fNodePath = fNodePath.substring(DataCacheNode.length ()).trim ();
			fNodePathDone = DataCacheNode;
			fNodePointer = DataCachePointer;
		}
		else
		{
			fNodePathDone = "";
			fNodePointer = Data;
		}

		boolean fContinueCheck = true;
		String fIteratorKey;
		String fIteratorValue;
		directArray fNodeArray;
		String fNodeName;
		directArray fNodeNsArray;
		boolean fNodeNsCheck;
		String fNodeNsName;
		int fNodePosition;
		directArray fNodePositionArray;
		LinkedList fNodesArray = new LinkedList (Arrays.asList (fNodePath.split (" ")));
		String fNsName;
		Object fObject;
		Pattern fReAttributesXmlns = Pattern.compile ("xmlns\\:(.+?)$",Pattern.CASE_INSENSITIVE);
		Pattern fReNodeNameXmlns = Pattern.compile ("^(.+?):(\\w+)$");
		Pattern fReNodePosition = Pattern.compile ("^(.+?)\\#(\\d+)$");
		Matcher fReResultObject;

		while ((fContinueCheck)&&(fNodesArray.size () > 0))
		{
			fContinueCheck = false;
			fNodeName = (String)fNodesArray.removeFirst ();
			fReResultObject = fReNodePosition.matcher (fNodeName);

			if (fReResultObject.matches ())
			{
				fNodeName = fReResultObject.group (1);
				fNodePosition = Integer.parseInt (fReResultObject.group (2));
			}
			else { fNodePosition = -1; }

			if (fNodesArray.size () > 0)
			{
				if (fNodePointer.keyExists (fNodeName))
				{
					fObject = fNodePointer.valueGet (fNodeName);
					fNodeArray = ((fObject instanceof directArray) ? (directArray)fObject : null);

					if ((fNodeArray != null)&&(fNodeArray.keyExists ("xml.mtree")))
					{
						if (fNodePosition >= 0)
						{
							if (fNodeArray.keyExists (fNodePosition))
							{
								fContinueCheck = true;
								fObject = fNodeArray.valueGet (fNodePosition);
								fReturn = true;

								if ((!(fObject instanceof directArray))||(!fNodeArray.valueGetArray(fNodePosition).keyExists ("xml.item")))
								{
									directArray fWrappedObjectArray = new directArray ("xml.item",fObject);
									fNodeArray.valueSet (fNodePosition,fWrappedObjectArray);
									fObject = fWrappedObjectArray;
								}

								fNodePointer = (directArray)fObject;
							}
						}
						else if (fNodeArray.keyExists (fNodeArray.valueGet ("xml.mtree")))
						{
							fContinueCheck = true;
							fNodePosition = Integer.parseInt ((String)fNodeArray.valueGet ("xml.mtree"));
							fObject = fNodeArray.valueGet (fNodePosition);
							fReturn = true;

							if ((!(fObject instanceof directArray))||(!fNodeArray.valueGetArray(fNodePosition).keyExists ("xml.item")))
							{
								directArray fWrappedObjectArray = new directArray ("xml.item",fObject);
								fNodeArray.valueSet (fNodePosition,fWrappedObjectArray);
								fObject = fWrappedObjectArray;
							}

							fNodePointer = (directArray)fObject;
						}
					}
					else if ((fNodeArray != null)&&(fNodeArray.keyExists ("xml.item")))
					{
						fContinueCheck = true;
						fNodePointer = fNodeArray;
					}
					else
					{
						fContinueCheck = true;

						if ((fNodePointer.keyExists ("xml.item"))&&(fNodePointer.valueGetArray("xml.item").keyExists ("level"))) { fNodeArray.valueSet ("level",(Integer.parseInt ((String)fNodePointer.valueGetArray("xml.item").valueGet ("level")) + 1),true); }
						else { fNodeArray.valueSet ("level",1,true); }

						directArray fWrappedNodeArray = new directArray ("xml.item",fNodeArray);
						fNodePointer.valueSet (fNodeName,fWrappedNodeArray,true);
						fNodePointer = fWrappedNodeArray;
					}
				}

				if ((!fContinueCheck)&&(fAddRecursively))
				{
					fContinueCheck = true;
					int fNodeLevel = 1;
					if (fNodePointer.valueGetArray("xml.item").keyExists ("level")) { fNodeLevel = (Integer.parseInt ((String)fNodePointer.valueGetArray("xml.item").valueGet ("level")) + 1); }

					fNodeArray = new directArray ("tag",fNodeName);
					fNodeArray.keyAdd ("level",fNodeLevel);

					if (fNodePointer.valueGetArray("xml.item").keyExists ("xmlns")) { fNodeArray.keyAdd ("xmlns",(fNodePointer.valueGetArray("xml.item").valueGet ("xmlns"))); }
					else { fNodeArray.keyAdd ("xmlns",(new directArray ())); }

					directArray fWrappedNodeArray = new directArray ("xml.item",fNodeArray);
					fNodePointer.valueSet (fNodeName,fWrappedNodeArray,true);
					fNodePointer = fWrappedNodeArray;
				}

				if (fNodePathDone.length () > 0) { fNodePathDone += " "; }
				fNodePathDone += fNodeName;
			}
			else
			{
				fNodeArray = new directArray ("tag",fNodeName);
				fNodeArray.keyAdd ("value",fValue);
				fNodeNsCheck = true;
				fNodeNsName = "";

				if ((fNodePointer.keyExists ("xml.item"))&&(fNodePointer.valueGetArray("xml.item").keyExists ("xmlns"))) { fNodeArray.keyAdd ("xmlns",(fNodePointer.valueGetArray("xml.item").valueGet ("xmlns"))); }
				else { fNodeArray.keyAdd ("xmlns",(new directArray ())); }

				fNodeNsArray = fNodeArray.valueGetArray ("xmlns");

				if ((fAttributes != null)&&(fAttributes.size () > 0))
				{
					if (fAttributes.keyExists ("xmlns"))
					{
						fObject = fAttributes.valueGet ("xmlns");
						fNodeNsName = ((fObject instanceof String) ? (String)fObject : "");

						if (fNodeNsName.length () > 0)
						{
							if (DataNsDefault.keyExists (fNodeNsName))
							{
								fNodeNsArray.valueSet ("@",(DataNsDefault.valueGet (fNodeNsName)));
								fNodeNsName = (DataNsDefault.valueGet (fNodeNsName) + ":" + fNodeName);
							}
							else
							{
								DataNsCounter++;
								DataNsDefault.valueSet (fNodeNsName,DataNsCounter);
								DataNsCompact.valueSet (DataNsCounter,fNodeNsName);
								fNodeNsArray.valueSet ("@",DataNsCounter);
								fNodeNsName = (DataNsCounter + ":" + fNodeName);
							}
						}
						else if (fNodeNsArray.keyExists ("@")) { fNodeNsArray.keyRemove ("@"); }
					}

					for (Iterator fIterator = fAttributes.keySet().iterator ();fIterator.hasNext ();)
					{
						fIteratorKey = (String)fIterator.next ();
						fIteratorValue = (String)fAttributes.valueGet (fIteratorKey);
						fReResultObject = fReAttributesXmlns.matcher (fIteratorKey);

						if ((fReResultObject != null)&&(fReResultObject.matches ()))
						{
							fNsName = fReResultObject.group (1);

							if (fIteratorValue.length () > 0)
							{
								if (DataNsDefault.keyExists (fIteratorValue)) { fNodeNsArray.valueSet (fNsName,(DataNsDefault.valueGet (fIteratorValue)),true); }
								else { fNodeNsArray.valueSet (fNsName,fIteratorValue,true); }
							}
							else if (fNodeNsArray.keyExists (fNsName)) { fNodeNsArray.keyRemove(fNsName); }
						}
					}

					fNodeArray.keyAdd ("attributes",fAttributes);
				}

				fReResultObject = fReNodeNameXmlns.matcher (fNodeName);

				if (fReResultObject.matches ())
				{
					try
					{
						if ((fNodeNsArray.keyExists (fReResultObject.group (1)))&&(Integer.parseInt ((String)fNodeNsArray.valueGet (fReResultObject.group (1))) > -1)) { fNodeNsName = (fNodeNsArray.valueGet (fReResultObject.group (1)) + ":" + (fReResultObject.group (2))); }
					}
					catch (NumberFormatException fHandledException) { fNodeNsCheck = false; }
				}
				else if (fNodeNsArray.keyExists ("@")) { fNodeNsName = (fNodeNsArray.valueGet ("@") + ":" + fNodeName); }
				else { fNodeNsCheck = false; }

				if (fNodePathDone.length () > 0)
				{
					fNsName = fNodePathDone + " " + fNodeName;

					if (fNodeNsCheck) { DataNsPredefinedCompact.keyAdd (fNsName,(DataNsPredefinedCompact.valueGet (fNodePathDone) + " " + fNodeNsName)); }
					else { DataNsPredefinedCompact.keyAdd (fNsName,(DataNsPredefinedCompact.valueGet (fNodePathDone) + " " + fNodeName)); }

					DataNsPredefinedDefault.keyAdd (DataNsPredefinedCompact.valueGet (fNsName),fNsName);
				}
				else if (fNodeNsCheck)
				{
					DataNsPredefinedCompact.keyAdd (fNodeName,fNodeNsName);
					DataNsPredefinedDefault.keyAdd (fNodeNsName,fNodeName);
				}
				else
				{
					DataNsPredefinedCompact.keyAdd (fNodeName,fNodeName);
					DataNsPredefinedDefault.keyAdd (fNodeName,fNodeName);
				}

				if (fNodePointer.keyExists (fNodeName))
				{
					fObject = fNodePointer.valueGet (fNodeName);
					fNodePositionArray = ((fObject instanceof directArray) ? (directArray)fObject : null);

					if ((fNodePositionArray == null)||(!fNodePositionArray.keyExists ("xml.mtree")))
					{
						directArray fWrappedObjectArray = new directArray ("xml.mtree",1);
						fWrappedObjectArray.keyAdd (0,fObject);
						fWrappedObjectArray.keyAdd (1,fNodeArray);

						fNodePointer.valueSet (fNodeName,fWrappedObjectArray);
					}
					else
					{
						fNodePositionArray.valueSet ("xml.mtree",(Integer.parseInt ((String)fNodePositionArray.valueGet ("xml.mtree")) + 1));
						fNodePositionArray.valueSet (fNodePositionArray.valueGet ("xml.mtree"),fNodeArray,true);
					}
				}
				else { fNodePointer.keyAdd (fNodeName,fNodeArray); }

				fReturn = true;
			}
		}

		return fReturn;
	}

/**
	* Registers a namespace (URI) for later use with this XML bridge class.
	*
	* @param fNs Output relevant namespace definition
	* @param fURI Uniform Resource Identifier
	* @since v0.1.00
*/
	public synchronized void nsRegister (String fNs,String fURI)
	{
		if (Debug != null) { Debug.add ("xml_reader.nsRegister (" + fNs + "," + fURI + ")"); }
		DataNs.valueSet (fNs,fURI,true);

		if (!DataNsDefault.keyExists (fURI))
		{
			DataNsCounter++;
			DataNsDefault.keyAdd (fURI,DataNsCounter);
			DataNsCompact.keyAdd (DataNsCounter,fURI);
		}
	}

/**
	* Translates the tag value if a predefined namespace matches. The translated
	* tag will be saved as "tag_ns" and "tag_parsed".
	*
	* @param  fNode XML array node
	* @return Checked XML array node
	* @since  v0.1.00
*/
	public synchronized directArray nsTranslate (directArray fNode)
	{
		if (Debug != null) { Debug.add ("xml_reader.nsTranslate (fNode)"); }
		directArray fReturn = fNode;

		Object fObject = fNode.valueGet ("xmlns",null);
		directArray fNodeXmlns = ((fObject instanceof directArray) ? (directArray)fObject : null);

		if ((fNode.keyExists ("tag"))&&(fNodeXmlns != null))
		{
			fReturn.valueSet ("tag_ns","",true);
			fReturn.valueSet ("tag_parsed",((String)fNode.valueGet ("tag")),true);
			Pattern fReNodeNameXmlns = Pattern.compile ("^(.+?):(\\w+)$");
			Matcher fReResultObject = fReNodeNameXmlns.matcher ((String)fNode.valueGet ("tag"));

			if ((fReResultObject.matches ())&&(fNodeXmlns.keyExists (fReResultObject.group (1)))&&(DataNsCompact.keyExists (fNodeXmlns.valueGet (fReResultObject.group (1)))))
			{
				Object fTagNs = DataNs.keySearch (DataNsCompact.valueGet (fNodeXmlns.valueGet (fReResultObject.group (1))));

				if (fTagNs != null)
				{
					fReturn.valueSet ("tag_ns",(String)fTagNs,true);
					fReturn.valueSet ("tag_parsed",(fTagNs + ":" + (fReResultObject.group (2))),true);
				}
			}

			if (fNode.keyExists ("attributes"))
			{
				directArray fAttributes = fNode.valueGetArray ("attributes");
				String fIteratorKey;
				String fIteratorValue;
				directArray fReturnAttributes = new directArray ();

				for (Iterator fIterator = fAttributes.keySet().iterator ();fIterator.hasNext ();)
				{
					fIteratorKey = (String)fIterator.next ();
					fIteratorValue = (String)fAttributes.valueGet (fIteratorKey);
					fReResultObject = fReNodeNameXmlns.matcher (fIteratorKey);

					if ((fReResultObject.matches ())&&(fNodeXmlns.keyExists (fReResultObject.group (1)))&&(DataNsCompact.keyExists (fNodeXmlns.valueGet (fReResultObject.group (1)))))
					{
						Object fTagNs = DataNs.keySearch (DataNsCompact.valueGet (fNodeXmlns.valueGet (fReResultObject.group (1))));
						if (fTagNs != null) { fReturnAttributes.keyAdd ((fTagNs + ":" + (fReResultObject.group (2))),fIteratorValue); }
					}
					else { fReturnAttributes.keyAdd (fIteratorKey,fIteratorValue); }
				}

				fReturn.valueSet ("attributes",fReturnAttributes);
			}
		}

		return fReturn;
	}

/**
	* Checks input path for predefined namespaces converts it to the internal
	* path.
	*
	* @param  fNodePath Path to the new node - delimiter is space
	* @return Output node path
	* @since  v0.1.00
*/
	protected String nsTranslatePath (String fNodePath)
	{
		if (Debug != null) { Debug.add ("xml_reader.nsTranslatePath (" + fNodePath + ")"); }
		String fReturn = fNodePath;

		String[] fNodesArray = fNodePath.split (" ");

		int fNodes = fNodesArray.length;
		String fNodeName = "";
		fNodePath = null;
		Pattern fReNodeNameXmlns = Pattern.compile ("^(.+?):(\\w+)$");
		Matcher fReResultObject;

		for (int fI = 0;fI < fNodes;fI++)
		{
			fNodeName = fNodesArray[fI];

			if (fNodePath == null) { fNodePath = ""; }
			else { fNodePath += " "; }

			if (fNodeName.indexOf (":") < 0) { fNodePath += fNodeName; }
			else
			{
				fReResultObject = fReNodeNameXmlns.matcher (fNodeName);

				if (fReResultObject.matches ())
				{
					if (DataNs.keyExists (fReResultObject.group (1)))
					{
						if (DataNsDefault.keyExists (DataNs.valueGet (fReResultObject.group (1)))) { fNodePath += (DataNsDefault.valueGet (DataNs.valueGet (fReResultObject.group (1))) + ":" + (fReResultObject.group (2))); }
						else { fNodePath += (fReResultObject.group (1) + ":" + (fReResultObject.group (2))); }
					}
					else { fNodePath += (fReResultObject.group (1) + ":" + (fReResultObject.group (2))); }
				}
				else { fNodePath += fNodeName; }
			}
		}

		if ((fNodePath != null)&&(DataNsPredefinedDefault.keyExists (fNodePath))) { fReturn = (String)DataNsPredefinedDefault.valueGet (fNodePath); }
		return fReturn;
	}

/**
	* Clears the namespace cache.
	*
	* @since v0.1.00
*/
	public synchronized void nsUnregister () { nsUnregister (""); }
/**
	* Unregisters a namespace or clears the cache (if fNs is empty).
	*
	* @param fNs Output relevant namespace definition
	* @since v0.1.00
*/
	public synchronized void nsUnregister (String fNs)
	{
		if (Debug != null) { Debug.add ("xml_reader.nsUnregister (" + fNs + ")"); }

		if ((fNs != null)&&(fNs.length () > 0))
		{
			if (DataNs.keyExists (fNs))
			{
				DataNsCompact.keyRemove (DataNsDefault.valueGet (DataNs.valueGet (fNs)));
				DataNsDefault.keyRemove (DataNs.valueGet (fNs));
				DataNs.keyRemove (fNs);
			}
		}
		else
		{
			DataNs = new directArray ();
			DataNsCompact = new directArray ();
			DataNsCounter = 0;
			DataNsDefault = new directArray ();
			DataNsPredefinedCompact = new directArray ();
			DataNsPredefinedDefault = new directArray ();
		}
	}

/**
	* "Imports" a XML tree into the cache.
	*
	* @param  fXmlArray Input array
	* @return True on success
	* @since  v0.1.00
*/
	public synchronized boolean set (directArray fXmlArray) { return set (fXmlArray,false); }
/**
	* "Imports" a XML tree into the cache.
	*
	* @param  fXmlArray Input array
	* @param  fOverwrite True to overwrite the current (non-empty) cache
	* @return True on success
	* @since  v0.1.00
*/
	public synchronized boolean set (directArray fXmlArray,boolean fOverwrite)
	{
		if (Debug != null) { Debug.add ("xml_reader.set (fXmlArray,fOverwrite)"); }
		boolean fReturn = false;

		if ((Data == null)||(fOverwrite))
		{
			Data = fXmlArray;
			fReturn = true;
		}

		return fReturn;
	}

/**
	* Converts XML data into a multi-dimensional or merged array ...
	*
	* @param  fData Input XML data
	* @return Multi-dimensional XML tree or merged array; null on error
	* @since  v0.1.00
*/
	public synchronized directArray xml2array (String fData) { return xml2array (fData,true,true); }
/**
	* Converts XML data into a multi-dimensional or merged array ...
	*
	* @param  fData Input XML data
	* @param  fStrictStandard Be standard conform
	* @return Multi-dimensional XML tree or merged array; null on error
	* @since  v0.1.00
*/
	public synchronized directArray xml2array (String fData,boolean fTreemode) { return xml2array (fData,fTreemode,true); }
/**
	* Converts XML data into a multi-dimensional or merged array ...
	*
	* @param  fData Input XML data
	* @param  fStrictStandard Be standard conform
	* @param  fTreemode Create a multi-dimensional result
	* @return Multi-dimensional XML tree or merged array; null on error
	* @since  v0.1.00
*/
	public synchronized directArray xml2array (String fData,boolean fTreemode,boolean fStrictStandard)
	{
		if (Debug != null) { Debug.add ("xml_reader.xml2array (fData,fTreemode,fStrictStandard)"); }
		directArray fReturn = null;

		if (fTreemode) { fReturn = DataParser.xml2arrayDocument (fData,fStrictStandard); }
		else { fReturn = DataParser.xml2arrayDocumentMerged (fData); }

		if ((fTreemode)&&(DataParseOnly))
		{
			Data = null;
			DataCacheNode = "";
			DataCachePointer = null;
			nsUnregister ();
		}

		return fReturn;
	}
}

//j// EOF