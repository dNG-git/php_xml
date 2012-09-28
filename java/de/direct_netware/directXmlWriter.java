/*- coding: utf-8 -*/
//j// BOF

/*n// NOTE
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
* @license    http://www.direct-netware.de/redirect.php?licenses;mpl2
*             Mozilla Public License, v. 2.0
*/

package de.direct_netware;

import java.util.Arrays;
import java.util.Iterator;
import java.util.LinkedHashMap;
import java.util.LinkedList;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

/**
* This class extends the bridge between Java and XML to work with XML and
* create valid documents.
*
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @subpackage xml
* @since      v1.0.0
* @license    http://www.direct-netware.de/redirect.php?licenses;mpl2
*             Mozilla Public License, v. 2.0
*/
public class directXmlWriter extends directXmlReader
{
/* -------------------------------------------------------------------------
Extend the class
------------------------------------------------------------------------- */

/**
	* Constructor directXmlWriter ("UTF-8",-1,5,false)
	*
	* @param f_charset Charset to be added as information to XML output
	* @param f_time Current UNIX timestamp
	* @param f_timeout_count Retries before timing out
	* @param f_debug Debug flag
	* @since v0.1.00
*/
	public directXmlWriter () { this ("UTF-8",-1,5,false); }
	public directXmlWriter (String fCharset) { this (fCharset,-1,5,false); }
	public directXmlWriter (String fCharset,int fTime) { this (fCharset,fTime,5,false); }
	public directXmlWriter (String fCharset,int fTime,int fTimeoutCount) { this (fCharset,fTime,fTimeoutCount,false); }
	public directXmlWriter (String fCharset,int fTime,int fTimeoutCount,boolean fDebug) { super (fCharset,false,fTime,fTimeoutCount,fDebug); }

/**
	* Read and convert a simple multi-dimensional array into our XML tree.
	*
	* @param  fArray Input array
	* @param  fOverwrite True to overwrite the current (non-empty) cache
	* @return True on success
	* @since  v0.1.00
*/
	public boolean arrayImport (directArray fArray) { return arrayImport (fArray,false); }
	public synchronized boolean arrayImport (directArray fArray,boolean fOverwrite)
	{
		if (Debug != null) { Debug.add ("xml_handler.arrayImport (fArray,fOverwrite)"); }
		boolean fReturn = false;

		if ((Data == null)||(Data.size () < 1)||(fOverwrite))
		{
			fArray = arrayImportWalker (fArray);
			Data = fArray;
			fReturn = true;
		}

		return fReturn;
	}

/**
	* Read and convert a single dimensional of an array for our XML tree.
	*
	* @param  fArray Input array
	* @param  fParent Parent array of an multi-dimensional array
	* @param  fLevel Current level of an multi-dimensional array
	* @return Output Array
	* @since  v0.1.00
*/
	protected directArray arrayImportWalker (directArray fArray) { return arrayImportWalker (fArray,null,1); }
	protected directArray arrayImportWalker (directArray fArray,directArray fParent,int fLevel)
	{
		if (Debug != null) { Debug.add ("xml_handler.arrayImportWalker (fArray,fParent," + fLevel + ")"); }
		directArray fReturn = ((fParent == null) ? new directArray () : fParent);

		String fIteratorKey;
		Object fIteratorValue;
		directArray fNodeArray = null;

		for (Iterator fIterator = fArray.keySet().iterator ();fIterator.hasNext ();)
		{
			fIteratorKey = (String)fIterator.next ();
			fIteratorValue = fArray.valueGet (fIteratorKey);

			if (fIteratorKey.length () > 0)
			{
				if (fIteratorValue instanceof directArray)
				{
					directArray fWrappedNodeArray = new directArray ();
					fNodeArray = new directArray ();
					fNodeArray.keyAdd ("tag",fIteratorKey);
					fNodeArray.keyAdd ("level",fLevel);
					fNodeArray.keyAdd ("xmlns",(new directArray ()));
					fWrappedNodeArray.keyAdd ("xml.item",fNodeArray);

					fReturn.keyAdd (fIteratorKey,(arrayImportWalker ((directArray)fIteratorValue,fWrappedNodeArray,(fLevel + 1))));
				}
				else
				{
					fNodeArray = new directArray ();
					fNodeArray.keyAdd ("tag",fIteratorKey);
					fNodeArray.keyAdd ("value",fIteratorValue);
					fNodeArray.keyAdd ("xmlns",(new directArray ()));
					fReturn.keyAdd (fIteratorKey,fNodeArray);
				}
			}
		}

		return fReturn;
	}

/**
	* Convert the cached XML tree into a XML string.
	*
	* @param  fFlush True to delete the cache content
	* @param  fStrictStandard Be standard conform
	* @return Result string
	* @since  v0.1.00
*/
	public String cacheExport () { return cacheExport (false,true); }
	public String cacheExport (boolean fFlush) { return cacheExport (fFlush,true); }
	public synchronized String cacheExport (boolean fFlush,boolean fStrictStandard)
	{
		if (Debug != null) { Debug.add ("xml_handler.cacheExport (fFlush,fStrictStandard)"); }
		String fReturn;

		if ((Data == null)||(Data.size () < 1)) { fReturn = ""; }
		else
		{
			fReturn = array2xml (Data,fStrictStandard);
			if (fFlush) { Data = null; }
		}

		return fReturn;
	}

/**
	* Set the cache pointer to a specific node.
	*
	* @param  fNodePath Path to the node - delimiter is space
	* @return True on success
	* @since  v0.1.00
*/
	public synchronized boolean nodeCachePointer (String fNodePath)
	{
		if (Debug != null) { Debug.add ("xml_handler.nodeCachePointer (" + fNodePath + ")"); }
		boolean fReturn = false;

		fNodePath = nsTranslatePath (fNodePath);

		if (fNodePath == DataCacheNode) { fReturn = true; }
		else
		{
			directArray fNodePointer = nodeGetPointer (fNodePath);

			if (fNodePointer != null)
			{
				DataCacheNode = fNodePath;
				DataCachePointer = (directArray)fNodePointer;
				fReturn = true;
			}
		}

		return fReturn;
	}

/**
	* Change the attributes of a specified node. Note: XMLNS updates must be
	* handled by the calling code.
	*
	* @param  f_node_path Path to the new node - delimiter is space
	* @param  f_attributes Attributes of the node
	* @return boolean False on error
	* @since  v0.1.00
*/
	public synchronized boolean nodeChangeAttributes (String fNodePath,directArray fAttributes)
	{
		if (Debug != null) { Debug.add ("xml_handler.nodeChangeAttributes (" + fNodePath + ",fAttributes)"); }
		boolean fReturn = false;

		fNodePath = nsTranslatePath (fNodePath);
		directArray fNodePointer = nodeGetPointer (fNodePath);

		if (fNodePointer != null)
		{
			if (fNodePointer.keyExists ("xml.item")) { fNodePointer = fNodePointer.valueGetArray ("xml.item"); }
			fNodePointer.valueSet ("attributes",fAttributes,true);
			fReturn = true;
		}

		return fReturn;
	}

/**
	* Change the value of a specified node.
	*
	* @param  f_node_path Path to the new node - delimiter is space
	* @param  f_value Value for the new node
	* @return (boolean) False on error
	* @since  v0.1.00
*/
	public synchronized boolean nodeChangeValue (String fNodePath,String fValue)
	{
		if (Debug != null) { Debug.add ("xml_handler.nodeChangeValue (" + fNodePath + "," + fValue + ")"); }
		boolean fReturn = false;

		fNodePath = nsTranslatePath (fNodePath);
		directArray fNodePointer = nodeGetPointer (fNodePath);

		if (fNodePointer != null)
		{
			if (fNodePointer.keyExists ("xml.item")) { fNodePointer = fNodePointer.valueGetArray ("xml.item"); }
			fNodePointer.valueSet ("value",fValue,true);
			fReturn = true;
		}

		return fReturn;
	}

/**
	* Count the occurrence of a specified node.
	*
	* @param  f_node_path Path to the node - delimiter is space
	* @return (integer) Counted number off matching nodes
	* @since  v0.1.00
*/
	public synchronized int nodeCount (String fNodePath)
	{
		if (Debug != null) { Debug.add ("xml_handler.nodeCount (" + fNodePath + ")"); }
		int fReturn = 0;

/* -------------------------------------------------------------------------
Get the parent node of the target.
------------------------------------------------------------------------- */

		fNodePath = nsTranslatePath (fNodePath);
		LinkedList fNodePathArray = new LinkedList (Arrays.asList (fNodePath.split (" ")));
		String fNodeName;
		directArray fNodePointer;

		if (fNodePathArray.size () > 1)
		{
			fNodeName = (String)fNodePathArray.removeLast ();
			fNodePath = "";

			for (Iterator fIterator = fNodePathArray.iterator ();fIterator.hasNext ();)
			{
				if (fNodePath.length () > 0) { fNodePath += " "; }
				fNodePath += (String)fIterator.next ();
			}

			fNodePointer = nodeGetPointer (fNodePath);
		}
		else
		{
			fNodeName = fNodePath;
			fNodePointer = Data;
		}

		if ((fNodePointer != null)&&(fNodePointer.keyExists (fNodeName))) { fReturn = ((fNodePointer.valueGetArray(fNodeName).keyExists ("xml.mtree")) ? (fNodePointer.valueGetArray(fNodeName).size () - 1) : 1); }
		return fReturn;
	}

/**
	* Read a specified node including all children if applicable.
	*
	* @param  f_node_path Path to the node - delimiter is space
	* @param  boolean $f_remove_metadata False to not remove the xml.item node
	* @return (mixed) XML node array on success; false on error
	* @since  v0.1.00
*/
	public directArray nodeGet (String fNodePath) { return nodeGet (fNodePath,true); }
	public synchronized directArray nodeGet (String fNodePath,boolean fRemoveMetadata)
	{
		if (Debug != null) { Debug.add ("xml_handler.nodeGet (" + fNodePath + ",fRemoveMetadata)"); }
		directArray fReturn = null;

		fNodePath = nsTranslatePath (fNodePath);
		directArray fNodePointer = nodeGetPointer (fNodePath);

		if (fNodePointer != null)
		{
			fReturn = new directArray ((LinkedHashMap)fNodePointer.clone ());
			if ((fRemoveMetadata)&&(fReturn.keyExists ("xml.item"))) { fReturn.keyRemove ("xml.item"); }
		}

		return fReturn;
	}

/**
	* Returns the pointer to a specific node.
	*
	* @param  f_node_path Path to the node - delimiter is space
	* @return (mixed) XML node pointer on success; false on error
	* @since  v0.1.00
*/
	protected directArray nodeGetPointer (String fNodePath)
	{
		if (Debug != null) { Debug.add ("xml_handler.nodeGetPointer (" + fNodePath + ")"); }
		directArray fReturn = null;

		directArray fNodePointer;

		if ((DataCacheNode.length () > 0)&&(fNodePath.startsWith (DataCacheNode)))
		{
			fNodePath = fNodePath.substring(DataCacheNode.length ()).trim ();
			fNodePointer = DataCachePointer;
		}
		else { fNodePointer = Data; }

		boolean fContinueCheck = true;
		directArray fNodeArray;
		String fNodeName;
		LinkedList fNodePathArray = ((fNodePath.length () > 0) ? new LinkedList (Arrays.asList (fNodePath.split (" "))) : new LinkedList ());
		int fNodePosition;
		Pattern fReNodePosition = Pattern.compile ("^(.+?)\\#(\\d+)$");
		Matcher fReResultObject;

		while ((fContinueCheck)&&(fNodePathArray.size () > 0))
		{
			fContinueCheck = false;
			fNodeName = (String)fNodePathArray.removeFirst ();
			fReResultObject = fReNodePosition.matcher (fNodeName);

			if (fReResultObject.matches ())
			{
				fNodeName = fReResultObject.group (1);
				fNodePosition = Integer.parseInt (fReResultObject.group (2));
			}
			else { fNodePosition = -1; }

			if (fNodePointer.keyExists (fNodeName))
			{
				fNodeArray = fNodePointer.valueGetArray (fNodeName);

				if (fNodeArray.keyExists ("xml.mtree"))
				{
					if (fNodePosition >= 0)
					{
						if (fNodeArray.keyExists (fNodePosition))
						{
							fContinueCheck = true;
							fNodePointer = fNodeArray.valueGetArray (fNodePosition);
						}
					}
					else if (fNodeArray.keyExists (fNodeArray.valueGet ("xml.mtree")))
					{
						fContinueCheck = true;
						fNodePointer = fNodeArray.valueGetArray (fNodeArray.valueGet ("xml.mtree"));
					}
				}
				else
				{
					fContinueCheck = true;
					fNodePointer = fNodeArray;
				}
			}
		}

		if (fContinueCheck) { fReturn = fNodePointer; }
		return fReturn;
	}

/**
	* Remove a node and all children if applicable.
	*
	* @param  f_node_path Path to the node - delimiter is space
	* @return (boolean) False on error
	* @since  v0.1.00
*/
	public synchronized boolean nodeRemove (String fNodePath)
	{
		if (Debug != null) { Debug.add ("xml_handler.nodeRemove (" + fNodePath + ")"); }
		boolean fReturn = false;

/* -------------------------------------------------------------------------
Get the parent node of the target.
------------------------------------------------------------------------- */

		fNodePath = nsTranslatePath (fNodePath);
		String fNodeName;
		LinkedList fNodePathArray = new LinkedList (Arrays.asList (fNodePath.split (" ")));
		directArray fNodePointer;

		if (fNodePathArray.size () > 1)
		{
			fNodeName = (String)fNodePathArray.removeLast ();
			fNodePath = "";

			for (Iterator fIterator = fNodePathArray.iterator ();fIterator.hasNext ();)
			{
				if (fNodePath.length () > 0) { fNodePath += " "; }
				fNodePath += (String)fIterator.next ();
			}

			fNodePointer = nodeGetPointer (fNodePath);

			if ((DataCacheNode.length () > 0)&&(fNodePath.startsWith (DataCacheNode)))
			{
				DataCacheNode = "";
				DataCachePointer = null;
			}
		}
		else
		{
			fNodeName = fNodePath;
			fNodePointer = Data;

			DataCacheNode = "";
			DataCachePointer = null;
		}

		directArray fNodeArray;
		int fNodePosition;

		if (fNodePointer != null)
		{
			Matcher fReResultObject = Pattern.compile("^(.+?)\\#(\\d+)$").matcher (fNodeName);

			if (fReResultObject.matches ())
			{
				fNodeName = fReResultObject.group (1);
				fNodePosition = Integer.parseInt (fReResultObject.group (2));
			}
			else { fNodePosition = -1; }

			if (fNodePointer.keyExists (fNodeName))
			{
				fNodeArray = fNodePointer.valueGetArray (fNodeName);

				if (fNodeArray.keyExists ("xml.mtree"))
				{
					if (fNodePosition >= 0)
					{
						if (fNodeArray.keyExists (fNodePosition)) { fReturn = fNodeArray.keyRemove (fNodePosition); }
					}
					else if (fNodeArray.keyExists (fNodeArray.valueGet ("xml.mtree"))) { fReturn = fNodeArray.keyRemove (fNodeArray.valueGet ("xml.mtree")); }

/* -------------------------------------------------------------------------
Update the mtree counter or remove it if applicable.
------------------------------------------------------------------------- */

					if (fReturn)
					{
						fNodePosition = (Integer.parseInt ((String)fNodeArray.valueGet ("xml.mtree")) - 1);

						if (fNodePosition > 0)
						{
							directArray fWrappedNodeArray = new directArray ();
							fWrappedNodeArray.keyAdd ("xml.mtree",fNodePosition);
							fNodeArray.keyRemove ("xml.mtree");
							fNodePosition = 0;

							for (Iterator fIterator = fNodeArray.values().iterator ();fIterator.hasNext ();)
							{
								fWrappedNodeArray.keyAdd (fNodePosition,(fIterator.next ()));
								fNodePosition++;
							}
						}
						else
						{
							fNodeArray.keyRemove ("xml.mtree");
							fNodePointer.valueSet (fNodeName,(fNodeArray.values().toArray ()[0]));
						}
					}
				}
				else { fReturn = fNodeArray.keyRemove (fNodeName); }
			}
		}

		return fReturn;
	}

/**
	* Returns the registered namespace (URI) for a given XML NS or node name
	* containing the registered XML NS.
	*
	* @param  f_input XML NS or node name
	* @return (string) Namespace (URI)
	* @since  v0.1.00
*/
	public synchronized String nsGetURI (String fInput)
	{
		if (Debug != null) { Debug.add ("xml_handler.nsGetURI (" + fInput + ")"); }
		String fReturn = "";

		Matcher fReResultObject = Pattern.compile ("^(.+?):(\\w+)$").matcher (fInput);

		if (fReResultObject.matches ())
		{
			if (DataNs.keyExists (fReResultObject.group (1))) { fReturn = (String)DataNs.valueGet (fReResultObject.group (1)); }
		}
		else if (DataNs.keyExists (fInput)) { fReturn = (String)DataNs.valueGet (fInput); }

		return fReturn;
	}
}

//j// EOF