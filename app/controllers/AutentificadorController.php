<?php
require_once './models/AutentificadorJWT.php';

class AutentificadorController
{
    public function LoginCuenta($request, $response, $args)
    {
		$params = $request->getParsedBody();
        
        try{
            if(isset($params["usuario"]) && isset($params["clave"]))
            {
                $empleado = Empleado::obtenerEmpleado($params["usuario"]);

                if($empleado==null)
                    throw new Exception("El empleado no existe...");

                if (password_verify($params["clave"], $empleado->clave) && $empleado->estado=='activo')
                {
                    $datos=array("nombre"=>$empleado->nombre, "usuario"=>$params["usuario"], "perfil"=>$empleado->perfil);
                    $token=AutentificadorJWT::CrearToken($datos);

                    $payload=json_encode(array("mensaje"=>"Bienvenido, ".$params["usuario"]."!", "perfil"=>$empleado->perfil, "jwt"=>$token));
                    $response->getBody()->write($payload);
                }
                else
                    throw new Exception("Algun dato no es valido...");
            }
            else
                throw new Exception("Faltan campos por completar...");
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