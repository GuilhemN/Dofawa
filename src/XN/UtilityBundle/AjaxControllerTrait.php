<?php

namespace XN\UtilityBundle;

use Symfony\Component\HttpFoundation\JsonResponse;

trait AjaxControllerTrait
{
	public function createAdaptedResponse(array $params, $status = 200, $headers = array()){
		if($this->get('request')->isXmlHttpRequest())
			return $this->createJsonResponse($params, $status, $headers);
		else
			return $this->render('::layout.html.twig', $params);
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

	public static function entityToListEntry($entity)
	{
		return [ 'value' => $entity->getId(), 'text' => strval($entity) ];
	}

	public function unlockSession()
	{
		$this->get('session')->save();
	}
}
