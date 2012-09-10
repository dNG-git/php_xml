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
* @subpackage xml
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;mpl2
*             Mozilla Public License, v. 2.0
*/

package de.direct_netware;

import java.util.Collection;
import java.util.Hashtable;
import java.util.Iterator;
import java.util.LinkedHashMap;
import java.util.Set;

/**
	* The embedded Array class provides a homogenous API for HashMaps and
	* ArrayLists.
	*
	* @author     direct Netware Group
	* @copyright  (C) direct Netware Group - All rights reserved
	* @package    ext_core
	* @subpackage xml
	* @since      v1.0.0
	* @license    http://www.direct-netware.de/redirect.php?licenses;mpl2
	*             Mozilla Public License, v. 2.0
*/
public class directArray
{
	protected LinkedHashMap Data;
	protected String KeySeparator = " ";

	public directArray () { Data = new LinkedHashMap (); }
	public directArray (directArray fXmlArray) { Data = new LinkedHashMap (fXmlArray.getHashMap ()); }

	public directArray (double fKey,Object fValue)
	{
		this ();
		Data.put ((String.valueOf (fKey)),fValue);
	}

	public directArray (double fKey,int fValue)
	{
		this ();
		Data.put ((String.valueOf (fKey)),(String.valueOf (fValue)));
	}

	public directArray (double fKey,double fValue)
	{
		this ();
		Data.put ((String.valueOf (fKey)),(String.valueOf (fValue)));
	}

	public directArray (double fKey,float fValue)
	{
		this ();
		Data.put ((String.valueOf (fKey)),(String.valueOf (fValue)));
	}

	public directArray (double[] fValues)
	{
		this ();

		for (int fI = 0;fI < fValues.length;fI++) { Data.put ((String.valueOf (fI)),(String.valueOf (fValues[fI]))); }
	}

	public directArray (float fKey,Object fValue)
	{
		this ();
		Data.put ((String.valueOf (fKey)),fValue);
	}

	public directArray (float fKey,int fValue)
	{
		this ();
		Data.put ((String.valueOf (fKey)),(String.valueOf (fValue)));
	}

	public directArray (float fKey,double fValue)
	{
		this ();
		Data.put ((String.valueOf (fKey)),(String.valueOf (fValue)));
	}

	public directArray (float fKey,float fValue)
	{
		this ();
		Data.put ((String.valueOf (fKey)),(String.valueOf (fValue)));
	}

	public directArray (float[] fValues)
	{
		this ();

		for (int fI = 0;fI < fValues.length;fI++) { Data.put ((String.valueOf (fI)),(String.valueOf (fValues[fI]))); }
	}

	public directArray (Hashtable fData)
	{
		this ();

		Object fKey;

		for (Iterator fIterator = fData.keySet().iterator ();(fIterator.hasNext ());)
		{
			fKey = fIterator.next ();
			Data.put (fKey,fData.get (fKey));
		}
	}

	public directArray (int fKey,Object fValue)
	{
		this ();
		Data.put ((String.valueOf (fKey)),fValue);
	}

	public directArray (int fKey,int fValue)
	{
		this ();
		Data.put ((String.valueOf (fKey)),(String.valueOf (fValue)));
	}

	public directArray (int fKey,double fValue)
	{
		this ();
		Data.put ((String.valueOf (fKey)),(String.valueOf (fValue)));
	}

	public directArray (int fKey,float fValue)
	{
		this ();
		Data.put ((String.valueOf (fKey)),(String.valueOf (fValue)));
	}

	public directArray (int[] fValues)
	{
		this ();

		for (int fI = 0;fI < fValues.length;fI++) { Data.put ((String.valueOf (fI)),(String.valueOf (fValues[fI]))); }
	}

	public directArray (LinkedHashMap fData) { Data = fData; }

	public directArray (Object fKey,Object fValue)
	{
		this ();
		Data.put (fKey,fValue);
	}

	public directArray (Object fKey,int fValue)
	{
		this ();
		Data.put (fKey,(String.valueOf (fValue)));
	}

	public directArray (Object fKey,double fValue)
	{
		this ();
		Data.put (fKey,(String.valueOf (fValue)));
	}

	public directArray (Object fKey,float fValue)
	{
		this ();
		Data.put (fKey,(String.valueOf (fValue)));
	}

	public directArray (Object[] fValues)
	{
		this ();

		for (int fI = 0;fI < fValues.length;fI++) { Data.put ((String.valueOf (fI)),fValues[fI]); }
	}

	public directArray (Object[] fKeys,Object[] fValues)
	{
		this ();

		int fLength = fValues.length;
		if (fKeys.length > fValues.length) { fLength = fKeys.length; }

		for (int fI = 0;fI < fLength;fI++)
		{
			if (fLength > fValues.length) { Data.put (fKeys[fI],null); }
			else if (fLength > fKeys.length) { Data.put ((String.valueOf (fI)),(String.valueOf (fValues[fI]))); }
			else { Data.put (fKeys[fI],fValues[fI]); }
		}
	}

	public directArray (Object[] fKeys,int[] fValues)
	{
		this ();

		int fLength = fValues.length;
		if (fKeys.length > fValues.length) { fLength = fKeys.length; }

		for (int fI = 0;fI < fLength;fI++)
		{
			if (fLength > fValues.length) { Data.put (fKeys[fI],null); }
			else if (fLength > fKeys.length) { Data.put ((String.valueOf (fI)),(String.valueOf (fValues[fI]))); }
			else { Data.put (fKeys[fI],(String.valueOf (fValues[fI]))); }
		}
	}

	public directArray (Object[] fKeys,double[] fValues)
	{
		this ();

		int fLength = fValues.length;
		if (fKeys.length > fValues.length) { fLength = fKeys.length; }

		for (int fI = 0;fI < fLength;fI++)
		{
			if (fLength > fValues.length) { Data.put (fKeys[fI],null); }
			else if (fLength > fKeys.length) { Data.put ((String.valueOf (fI)),(String.valueOf (fValues[fI]))); }
			else { Data.put (fKeys[fI],(String.valueOf (fValues[fI]))); }
		}
	}

	public directArray (Object[] fKeys,float[] fValues)
	{
		this ();

		int fLength = fValues.length;
		if (fKeys.length > fValues.length) { fLength = fKeys.length; }

		for (int fI = 0;fI < fLength;fI++)
		{
			if (fLength > fValues.length) { Data.put (fKeys[fI],null); }
			else if (fLength > fKeys.length) { Data.put ((String.valueOf (fI)),(String.valueOf (fValues[fI]))); }
			else { Data.put (fKeys[fI],(String.valueOf (fValues[fI]))); }
		}
	}

	public synchronized Object clone () { return Data.clone (); }
	public synchronized Object get (Object fKey) { return Data.get (fKey); }
	public synchronized LinkedHashMap getHashMap () { return Data; }

	public boolean keyAdd (int fKey,Object fValue) { return keyAdd ((String.valueOf (fKey)),fValue,false); }
	public boolean keyAdd (int fKey,Object fValue,boolean fOverwrite) { return keyAdd ((String.valueOf (fKey)),fValue,fOverwrite); }
	public boolean keyAdd (int fKey,int fValue) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean keyAdd (int fKey,int fValue,boolean fOverwrite) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),fOverwrite); }
	public boolean keyAdd (int fKey,double fValue) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean keyAdd (int fKey,double fValue,boolean fOverwrite) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),fOverwrite); }
	public boolean keyAdd (int fKey,float fValue) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean keyAdd (int fKey,float fValue,boolean fOverwrite) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),fOverwrite); }
	public boolean keyAdd (double fKey,Object fValue) { return keyAdd ((String.valueOf (fKey)),fValue,false); }
	public boolean keyAdd (double fKey,Object fValue,boolean fOverwrite) { return keyAdd ((String.valueOf (fKey)),fValue,fOverwrite); }
	public boolean keyAdd (double fKey,int fValue) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean keyAdd (double fKey,int fValue,boolean fOverwrite) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),fOverwrite); }
	public boolean keyAdd (double fKey,double fValue) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean keyAdd (double fKey,double fValue,boolean fOverwrite) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),fOverwrite); }
	public boolean keyAdd (double fKey,float fValue) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean keyAdd (double fKey,float fValue,boolean fOverwrite) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),fOverwrite); }
	public boolean keyAdd (float fKey,Object fValue) { return keyAdd ((String.valueOf (fKey)),fValue,false); }
	public boolean keyAdd (float fKey,Object fValue,boolean fOverwrite) { return keyAdd ((String.valueOf (fKey)),fValue,fOverwrite); }
	public boolean keyAdd (float fKey,int fValue) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean keyAdd (float fKey,int fValue,boolean fOverwrite) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),fOverwrite); }
	public boolean keyAdd (float fKey,double fValue) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean keyAdd (float fKey,double fValue,boolean fOverwrite) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),fOverwrite); }
	public boolean keyAdd (float fKey,float fValue) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean keyAdd (float fKey,float fValue,boolean fOverwrite) { return keyAdd ((String.valueOf (fKey)),(String.valueOf (fValue)),fOverwrite); }
	public boolean keyAdd (Object fKey,Object fValue) { return keyAdd (fKey,fValue,false); }
	public boolean keyAdd (Object fKey,int fValue) { return keyAdd (fKey,(String.valueOf (fValue)),false); }
	public boolean keyAdd (Object fKey,int fValue,boolean fOverwrite) { return keyAdd (fKey,(String.valueOf (fValue)),fOverwrite); }
	public boolean keyAdd (Object fKey,double fValue) { return keyAdd (fKey,(String.valueOf (fValue)),false); }
	public boolean keyAdd (Object fKey,double fValue,boolean fOverwrite) { return keyAdd (fKey,(String.valueOf (fValue)),fOverwrite); }
	public boolean keyAdd (Object fKey,float fValue) { return keyAdd (fKey,(String.valueOf (fValue)),false); }
	public boolean keyAdd (Object fKey,float fValue,boolean fOverwrite) { return keyAdd (fKey,(String.valueOf (fValue)),fOverwrite); }

	public synchronized boolean keyAdd (Object fKey,Object fValue,boolean fOverwrite)
	{
		boolean fReturn = false;

		if (fKey instanceof String)
		{
			String fKeys[] = { (String)fKey };
			if (fKeys[0].indexOf (KeySeparator) > -1) { fKeys = fKeys[0].split (KeySeparator,2); }

			if (fKeys.length > 1)
			{
				if (Data.containsKey (fKeys[0]))
				{
					Object fObject = Data.get (fKeys[0]);

					if (fObject instanceof directArray)
					{
						directArray fDict = (directArray)fObject;
						fReturn = fDict.keyAdd (fKeys[1],fValue,fOverwrite);
						fKey = null;
					}
				}
			}
		}

		if ((!fReturn)&&(fKey != null)&&(fValue != null))
		{
			if (Data.containsKey (fKey))
			{
				if (fOverwrite)
				{
					fReturn = true;
					Data.put (fKey,fValue);
				}
				else { fReturn = false; }
			}
			else
			{
				fReturn = true;
				Data.put (fKey,fValue);
			}
		}

		return fReturn;
	}

	public boolean keyExists (int fKey) { return keyExists (String.valueOf (fKey)); }
	public boolean keyExists (double fKey) { return keyExists (String.valueOf (fKey)); }
	public boolean keyExists (float fKey) { return keyExists (String.valueOf (fKey)); }

	public synchronized boolean keyExists (Object fKey)
	{
		boolean fReturn = false;

		if (fKey instanceof String)
		{
			String fKeys[] = { (String)fKey };
			if (fKeys[0].indexOf (KeySeparator) > -1) { fKeys = fKeys[0].split (KeySeparator,2); }

			if (fKeys.length > 1)
			{
				if (Data.containsKey (fKeys[0]))
				{
					Object fObject = Data.get (fKeys[0]);

					if (fObject instanceof directArray)
					{
						directArray fDict = (directArray)fObject;
						fReturn = fDict.keyExists (fKeys[1]);
					}
				}
			}
		}

		if ((fKey != null)&&(!fReturn)) { fReturn = Data.containsKey (fKey); }

		return fReturn;
	}

	public int keyGetHash (int fKey) throws ArrayKeyNotFound { return keyGetHash (String.valueOf (fKey)); }
	public int keyGetHash (double fKey) throws ArrayKeyNotFound { return keyGetHash (String.valueOf (fKey)); }
	public int keyGetHash (float fKey) throws ArrayKeyNotFound { return keyGetHash (String.valueOf (fKey)); }

	protected int keyGetHash (Object fKey) throws ArrayKeyNotFound
	{
		int fReturn = 0;

		if (fKey instanceof String)
		{
			String fKeys[] = { (String)fKey };
			if (fKeys[0].indexOf (KeySeparator) > -1) { fKeys = fKeys[0].split (KeySeparator,2); }

			if (fKeys.length > 1)
			{
				if (Data.containsKey (fKeys[0]))
				{
					Object fObject = Data.get (fKeys[0]);

					if (fObject instanceof directArray)
					{
						directArray fDict = (directArray)fObject;
						fReturn = fDict.keyGetHash (fKeys[1]);
					}
				}
			}
			else if ((fKeys.length == 1)&&(Data.containsKey (fKey))) { fReturn = fKey.hashCode (); }
			else { throw new ArrayKeyNotFound (); }
		}
		else if ((fKey == null)||(!Data.containsKey (fKey))) { throw new ArrayKeyNotFound (); }
		else { fReturn = fKey.hashCode (); }

		return fReturn;
	}

	public boolean keyRemove (int fKey) { return keyRemove ((String.valueOf (fKey)),true); }
	public boolean keyRemove (double fKey) { return keyRemove ((String.valueOf (fKey)),true); }
	public boolean keyRemove (float fKey) { return keyRemove ((String.valueOf (fKey)),true); }
	public boolean keyRemove (Object fKey) { return keyRemove (fKey,true); }

	public synchronized boolean keyRemove (Object fKey,boolean fIgnoreMissing)
	{
		boolean fReturn = false;

		if (fKey instanceof String)
		{
			String fKeys[] = { (String)fKey };
			if (fKeys[0].indexOf (KeySeparator) > -1) { fKeys = fKeys[0].split (KeySeparator,2); }

			if (fKeys.length > 1)
			{
				if (Data.containsKey (fKeys[0]))
				{
					Object fObject = Data.get (fKeys[0]);

					if (fObject instanceof directArray)
					{
						directArray fDict = (directArray)fObject;
						fReturn = fDict.keyRemove (fKeys[1],fIgnoreMissing);
						fKey = null;
					}
				}
			}
		}

		if (!fReturn)
		{
			if ((fKey == null)||(!Data.containsKey (fKey)))
			{
				if (!fIgnoreMissing) { fReturn = false; }
			}
			else { Data.remove (fKey); }
		}

		return fReturn;
	}

	public synchronized Set keySet () { return Data.keySet (); }

	public Object keySearch (int fValue) { return keySearch (String.valueOf (fValue)); }
	public Object keySearch (double fValue) { return keySearch (String.valueOf (fValue)); }
	public Object keySearch (float fValue) { return keySearch (String.valueOf (fValue)); }
	public synchronized Object keySearch (Object fValue)
	{
		Object fReturn = null;

		Object fIteratorKey;
		Object fIteratorValue;

		for (Iterator fIterator = Data.keySet().iterator ();((fIterator.hasNext ())&&(fReturn == null));)
		{
			fIteratorKey = fIterator.next ();
			fIteratorValue = Data.get (fIteratorKey);

			if (fIteratorValue.equals (fValue)) { fReturn = fIteratorKey; }
		}

		return fReturn;
	}

	public synchronized int size () { return Data.size (); }

	public Object valueGet (int fKey) { return valueGet (String.valueOf (fKey),null); }
	public Object valueGet (double fKey) { return valueGet (String.valueOf (fKey),null); }
	public Object valueGet (float fKey) { return valueGet (String.valueOf (fKey),null); }
	public Object valueGet (Object fKey) { return valueGet (fKey,null); }

	public Object valueGet (int fKey,Object fDefaultValue) { return valueGet (String.valueOf (fKey),fDefaultValue); }
	public Object valueGet (double fKey,Object fDefaultValue) { return valueGet (String.valueOf (fKey),fDefaultValue); }
	public Object valueGet (float fKey,Object fDefaultValue) { return valueGet (String.valueOf (fKey),fDefaultValue); }
	public Object valueGet (Object fKey,Object fDefaultValue)
	{
		Object fReturn = null;

		if (fKey instanceof String)
		{
			String fKeys[] = { (String)fKey };
			if (fKeys[0].indexOf (KeySeparator) > -1) { fKeys = fKeys[0].split (KeySeparator,2); }

			if (fKeys.length > 1)
			{
				if (Data.containsKey (fKeys[0]))
				{
					Object fObject = Data.get (fKeys[0]);

					if (fObject instanceof directArray)
					{
						directArray fDict = (directArray)fObject;
						fReturn = fDict.valueGet (fKeys[1]);
					}
				}
			}
		}

		if (fReturn == null)
		{
			if ((fKey != null)&&(Data.containsKey (fKey))) { fReturn = Data.get (fKey); }
			else { fReturn = fDefaultValue; }
		}

		return fReturn;
	}

	public directArray valueGetArray (int fKey) { return valueGetArray (String.valueOf (fKey)); }
	public directArray valueGetArray (double fKey) { return valueGetArray (String.valueOf (fKey)); }
	public directArray valueGetArray (float fKey) { return valueGetArray (String.valueOf (fKey)); }
	public directArray valueGetArray (Object fKey)
	{
		Object fReturn = valueGet (fKey);
		if ((fReturn == null)||(!(fReturn instanceof directArray))) { throw new ArrayKeyNotFound (); }
		return (directArray)fReturn;
	}

	public synchronized Collection values () { return Data.values (); }

	public boolean valueSet (int fKey,Object fValue) { return valueSet ((String.valueOf (fKey)),fValue,false); }
	public boolean valueSet (int fKey,Object fValue,boolean fAdd) { return valueSet ((String.valueOf (fKey)),fValue,fAdd); }
	public boolean valueSet (int fKey,int fValue) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean valueSet (int fKey,int fValue,boolean fAdd) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),fAdd); }
	public boolean valueSet (int fKey,double fValue) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean valueSet (int fKey,double fValue,boolean fAdd) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),fAdd); }
	public boolean valueSet (int fKey,float fValue) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean valueSet (int fKey,float fValue,boolean fAdd) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),fAdd); }
	public boolean valueSet (double fKey,Object fValue) { return valueSet ((String.valueOf (fKey)),fValue,false); }
	public boolean valueSet (double fKey,Object fValue,boolean fAdd) { return valueSet ((String.valueOf (fKey)),fValue,fAdd); }
	public boolean valueSet (double fKey,int fValue) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean valueSet (double fKey,int fValue,boolean fAdd) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),fAdd); }
	public boolean valueSet (double fKey,double fValue) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean valueSet (double fKey,double fValue,boolean fAdd) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),fAdd); }
	public boolean valueSet (double fKey,float fValue) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean valueSet (double fKey,float fValue,boolean fAdd) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),fAdd); }
	public boolean valueSet (float fKey,Object fValue) { return valueSet ((String.valueOf (fKey)),fValue,false); }
	public boolean valueSet (float fKey,Object fValue,boolean fAdd) { return valueSet ((String.valueOf (fKey)),fValue,fAdd); }
	public boolean valueSet (float fKey,int fValue) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean valueSet (float fKey,int fValue,boolean fAdd) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),fAdd); }
	public boolean valueSet (float fKey,double fValue) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean valueSet (float fKey,double fValue,boolean fAdd) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),fAdd); }
	public boolean valueSet (float fKey,float fValue) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),false); }
	public boolean valueSet (float fKey,float fValue,boolean fAdd) { return valueSet ((String.valueOf (fKey)),(String.valueOf (fValue)),fAdd); }
	public boolean valueSet (Object fKey,Object fValue) { return valueSet (fKey,fValue,false); }
	public boolean valueSet (Object fKey,int fValue) { return valueSet (fKey,(String.valueOf (fValue)),false); }
	public boolean valueSet (Object fKey,int fValue,boolean fAdd) { return valueSet (fKey,(String.valueOf (fValue)),fAdd); }
	public boolean valueSet (Object fKey,double fValue) { return valueSet (fKey,(String.valueOf (fValue)),false); }
	public boolean valueSet (Object fKey,double fValue,boolean fAdd) { return valueSet (fKey,(String.valueOf (fValue)),fAdd); }
	public boolean valueSet (Object fKey,float fValue) { return valueSet (fKey,(String.valueOf (fValue)),false); }
	public boolean valueSet (Object fKey,float fValue,boolean fAdd) { return valueSet (fKey,(String.valueOf (fValue)),fAdd); }

	public synchronized boolean valueSet (Object fKey,Object fValue,boolean fAdd)
	{
		boolean fReturn = false;

		if (fKey instanceof String)
		{
			String fKeys[] = { (String)fKey };
			if (fKeys[0].indexOf (KeySeparator) > -1) { fKeys = fKeys[0].split (KeySeparator,2); }

			if (fKeys.length > 1)
			{
				if (Data.containsKey (fKeys[0]))
				{
					Object fObject = Data.get (fKeys[0]);

					if (fObject instanceof directArray)
					{
						directArray fDict = (directArray)fObject;
						fReturn = fDict.valueSet (fKeys[1],fValue,fAdd);
						fKey = null;
					}
				}
			}
		}

		if ((!fReturn)&&(fKey != null)&&(fValue != null))
		{
			if (Data.containsKey (fKey))
			{
				fReturn = true;
				Data.put (fKey,fValue);
			}
			else if (fAdd) { fReturn = keyAdd (fKey,fValue); }
			else { fReturn = false; }
		}

		return fReturn;
	}

	public void valuesSet (directArray fArray) { Data.putAll (fArray.getHashMap ()); }
	public void valuesSet (LinkedHashMap fHashMap) { Data.putAll (fHashMap); }

/**
	* "ArrayKeyNotFound" is thrown if the key has not been found or has an invalid
	* data type (in case of "valueGetArray ()").
	*
	* @author     direct Netware Group
	* @copyright  (C) direct Netware Group - All rights reserved
	* @package    ext_core
	* @subpackage xml
	* @since      v1.0.0
	* @license    http://www.direct-netware.de/redirect.php?licenses;mpl2
	*             Mozilla Public License, v. 2.0
*/
	public static class ArrayKeyNotFound extends RuntimeException { private static final long serialVersionUID = 823912917304453331L; }
}

//j// EOF