<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MWAutenticadorSocio
{
	public function __invoke(Request $request, RequestHandler $handler) : Response
	{
		$header = $request->getHeaderLine("Authorization");
		$response = new Response();

		try{
			$token = trim(explode("Bearer", $header)[1]);
			json_encode(array('datos'=>AutentificadorJWT::VerificarToken($token)));

			$data=AutentificadorJWT::ObtenerData($token);

			if($data->perfil=="socio")
				$response = $handler->handle($request);
			else
				throw new Exception("Solo socios tienen permitido esta operación...");
		}
		catch(Exception $e)
		{
			$payload=json_encode(array('error'=>$e->getMessage()));
			$response->getBody()->write($payload);
			$response = $response->withStatus(401);
		}

		return $response
			->withHeader('Content-Type', 'application/json');
	}
}

class MWAutenticadorMozo
{
	public function __invoke(Request $request, RequestHandler $handler) : Response
	{
		$header = $request->getHeaderLine("Authorization");
		$response = new Response();

		try{
			$token = trim(explode("Bearer", $header)[1]);
			json_encode(array('datos'=>AutentificadorJWT::VerificarToken($token)));

			$data=AutentificadorJWT::ObtenerData($token);

			if($data->perfil=="mozo")
				$response = $handler->handle($request);
			else
				throw new Exception("Solo mozos tienen permitido esta operación...");
		}
		catch(Exception $e)
		{
			$payload=json_encode(array('error'=>$e->getMessage()));
			$response->getBody()->write($payload);
			$response = $response->withStatus(401);
		}

		return $response
			->withHeader('Content-Type', 'application/json');
	}
}

class MWAutenticadorCocinero
{
	public function __invoke(Request $request, RequestHandler $handler) : Response
	{
		$header = $request->getHeaderLine("Authorization");
		$response = new Response();

		try{
			$token = trim(explode("Bearer", $header)[1]);
			json_encode(array('datos'=>AutentificadorJWT::VerificarToken($token)));

			$data=AutentificadorJWT::ObtenerData($token);

			if($data->perfil=="cocinero")
				$response = $handler->handle($request);
			else
				throw new Exception("Solo cocineros tienen permitido esta operación...");
		}
		catch(Exception $e)
		{
			$payload=json_encode(array('error'=>$e->getMessage()));
			$response->getBody()->write($payload);
			$response = $response->withStatus(401);
		}

		return $response
			->withHeader('Content-Type', 'application/json');
	}
}

class MWAutenticadorBartender
{
	public function __invoke(Request $request, RequestHandler $handler) : Response
	{
		$header = $request->getHeaderLine("Authorization");
		$response = new Response();

		try{
			$token = trim(explode("Bearer", $header)[1]);
			json_encode(array('datos'=>AutentificadorJWT::VerificarToken($token)));

			$data=AutentificadorJWT::ObtenerData($token);

			if($data->perfil=="bartender")
				$response = $handler->handle($request);
			else
				throw new Exception("Solo bartenders tienen permitido esta operación...");
		}
		catch(Exception $e)
		{
			$payload=json_encode(array('error'=>$e->getMessage()));
			$response->getBody()->write($payload);
			$response = $response->withStatus(401);
		}

		return $response
			->withHeader('Content-Type', 'application/json');
	}
}

class MWAutenticadorCervecero
{
	public function __invoke(Request $request, RequestHandler $handler) : Response
	{
		$header = $request->getHeaderLine("Authorization");
		$response = new Response();

		try{
			$token = trim(explode("Bearer", $header)[1]);
			json_encode(array('datos'=>AutentificadorJWT::VerificarToken($token)));

			$data=AutentificadorJWT::ObtenerData($token);

			if($data->perfil=="cervecero")
				$response = $handler->handle($request);
			else
				throw new Exception("Solo cerveceros tienen permitido esta operación...");
		}
		catch(Exception $e)
		{
			$payload=json_encode(array('error'=>$e->getMessage()));
			$response->getBody()->write($payload);
			$response = $response->withStatus(401);
		}

		return $response
			->withHeader('Content-Type', 'application/json');
	}
}

class MWAutenticadorEmpleado
{
	public function __invoke(Request $request, RequestHandler $handler) : Response
	{
		$header = $request->getHeaderLine("Authorization");
		$response = new Response();

		try{
			$token = trim(explode("Bearer", $header)[1]);
			json_encode(array('datos'=>AutentificadorJWT::VerificarToken($token)));

			$data=AutentificadorJWT::ObtenerData($token);

			if($data->perfil!="socio")
				$response = $handler->handle($request);
			else
				throw new Exception("Solo empleados tienen permitido esta operación...");
		}
		catch(Exception $e)
		{
			$payload=json_encode(array('error'=>$e->getMessage()));
			$response->getBody()->write($payload);
			$response = $response->withStatus(401);
		}

		return $response
			->withHeader('Content-Type', 'application/json');
	}
}