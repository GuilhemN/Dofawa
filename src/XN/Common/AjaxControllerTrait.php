<?php

namespace XN\Common;

use Symfony\Component\HttpFoundation\JsonResponse;

trait AjaxControllerTrait
{
	public function createAdaptedResponse(array $params, $view = null, $status = 200, $headers = array()){
		if($view === null)
			$view = '::layout.html.twig';

		if($this->get('request')->isXmlHttpRequest())
			return $this->createJsonResponse($params, $status, $headers);
		else
			return $this->render($view, $params);
	}

	public function createJsonResponse($data = null, $status = 200, $headers = array())
	{
		return new JsonResponse($data, $status, $headers);
	}

	public function createJsonPResponse($data = null, $callback = null, $status = 200, $headers = array())
	{
		$response = new JsonResponse($data, $status, $headers);
		if ($callback !== null)
			$response->setCallback($callback);
		return $response;
	}

	public function unlockSession()
	{
		$this->get('session')->save();
	}
}
