<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Core;

/**
 * Class WebRequest
 *
 * @package Combodo\iTop\Core
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class WebRequest
{
	/** @var string URL of the request */
	protected $sURL;
	/** @var array cURL options */
	protected $aOptions;
	/**
	 * @var string Name of the callback to be called on request's response
	 *             - $this->Foo
	 *             - \SomeClass::Foo
	 */
	protected $sResponseCallbackName;
	/** @var array Structured data that will be passed to $sResponseCallbackName */
	protected $aResponseCallbackParams;

	/**
	 * @param string $sURL Url which should called with this request
	 */
	public function __construct($sURL)
	{
		$this->sURL = $sURL;
		$this->aOptions = array();
		$this->sResponseCallbackName = '';
		$this->aResponseCallbackParams = [];
	}

	/**
	 * Return the URL of the web request
	 *
	 * @return string
	 */
	public function GetURL()
	{
		return $this->sURL;
	}

	/**
	 * Return the option value for the $sOption code. If no option matching, null is returned.
	 *
	 * @param string $sOption Option code to return
	 *
	 * @return mixed|null
	 */
	public function GetOption($sOption)
	{
		if(!array_key_exists($sOption, $this->aOptions))
		{
			return null;
		}

		return $this->aOptions[$sOption];
	}

	/**
	 * Return all options.
	 *
	 * @return array
	 */
	public function GetOptions()
	{
		return $this->aOptions;
	}

	/**
	 * Set $sOption for curl call
	 *
	 * @param string $sOption name of curl option to be set
	 * @param string $sValue  value to be set
	 *
	 * @return void
	 */
	public function SetOption($sOption, $sValue)
	{
		$this->aOptions[$sOption] = $sValue;
	}

	/**
	 * Set multiple $aOptions for curl call at once.
	 * Note: Add / overwrite options present in $aOptions, but does not replace the entire array with $aOptions
	 *
	 * @param array $aOptions array with key values for options
	 *
	 * @return void
	 */
	public function SetOptions($aOptions)
	{
		foreach ($aOptions as $sOption => $sValue)
		{
			$this->aOptions[$sOption] = $sValue;
		}
	}

	/**
	 * @see \Combodo\iTop\Core\WebRequest::$sURL
	 * @return string
	 */
	public function GetResponseCallbackName()
	{
		return $this->sResponseCallbackName;
	}

	/**
	 * @param string $sCallbackName
	 *
	 * @see \Combodo\iTop\Core\WebRequest::$sResponseCallbackName
	 * @return $this
	 */
	public function SetResponseCallbackName($sCallbackName)
	{
		$this->sResponseCallbackName = $sCallbackName;
		return $this;
	}

	/**
	 * @see \Combodo\iTop\Core\WebRequest::$aResponseCallbackParams
	 * @return array
	 */
	public function GetResponseCallbackParams()
	{
		return $this->aResponseCallbackParams;
	}

	/**
	 * @param array $aParams
	 *
	 * @see \Combodo\iTop\Core\WebRequest::$aResponseCallbackParams
	 * @return $this
	 */
	public function SetResponseCallbackParams(array $aParams)
	{
		$this->aResponseCallbackParams = $aParams;
		return $this;
	}
}