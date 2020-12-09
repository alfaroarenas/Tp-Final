<?php


class SupervisorController
{
    private $render;
    private $usuarioModel;
    private $vehiculoModel;
    private $viajeModel;
    private $clienteModel;
    private $supervisorModel;


    public function __construct($render, $usuarioModel, $vehiculoModel, $viajeModel, $clienteModel, $supervisorModel)
    {
        $this->render = $render;
        $this->usuarioModel = $usuarioModel;
        $this->vehiculoModel = $vehiculoModel;
        $this->viajeModel = $viajeModel;
        $this->clienteModel = $clienteModel;
        $this->supervisorModel = $supervisorModel;

    }

    public function listarChoferes()
    {
        $choferes["choferes"] = $this->usuarioModel->listarChoferes();
        //  die($usuarios["usuarios"]);
        echo $this->render->render("view/partial/headerSupervisor.mustache", $_SESSION),
        $this->render->render("view/Choferes.php", $choferes),print_r($choferes);

    }

    public function prepararViaje()
    {
        $chofer = $this->usuarioModel->buscarUsuarioPorDni($_POST["dni"]);
        $viaje = $this->viajeModel->listarViajesParaAsignarVehiculo();
        $vehiculo = $this->vehiculoModel->listarVehiculosSupervisor();
        $arrastre = $this->vehiculoModel->listarArrastre();
        $datos["datos"] = array("chofer" => $chofer, "vehiculo" => $vehiculo, "arrastre" => $arrastre);
        echo $this->render->render("view/partial/headerSupervisor.mustache", $_SESSION),
        $this->render->render("view/PrepararViaje.php", $datos), print_r($datos), "<br>", print_r($arrastre);
    }

    public function asignarViaje()
    {
        $this->viajeModel->crearViaje($_POST["cliente"], $_POST["destino"], $_POST["kmviaje"], $_POST["matricula"], $_POST["patente"]);
        $this->usuarioModel->asignarVehiculoAChofer($_POST["matricula"], $_POST["dni"]);
        $this->vehiculoModel->cambiarEstadoDeVehiculoAOcupado($_POST["matricula"]);
        $this->volverAInicio();
    }

    public function detalleViaje()
    {
        $viaje["viaje"] = $this->viajeModel->mostrarViaje($_POST["dni"]);
        echo $this->render->render("view/partial/headerSupervisor.mustache", $_SESSION),
        $this->render->render("view/DetalleViaje.php", $viaje);
    }

    public function cargarProforma()
    {
        echo $this->render->render("view/partial/headerSupervisor.mustache"),
        $this->render->render("view/CargarProforma.php");
    }

    public function cargarClienteParaProforma()
    {
        $cliente = $this->clienteModel->buscarCliente($_POST["cuit"]);
        if ($cliente == null)
            $this->guardarNuevoCliente();

        $this->guardarViaje();
        $this->guardarCarga();
        $this->guardarDatosEstimados();

        $this->volverAInicio();
    }

    public function guardarNuevoCliente()
    {
        $denominacion = $_POST["denominacion"];
        $cuit = $_POST["cuit"];
        $direccion = $_POST["direccion"];
        $telefono = $_POST["telefono"];
        $email = $_POST["email"];
        $contacto1 = $_POST["contacto1"];
        $contacto2 = $_POST["contacto2"];
        $this->clienteModel->registrarCliente($denominacion, $cuit, $direccion, $telefono, $email, $contacto1, $contacto2);
    }

    public function guardarViaje()
    {
        $cliente = $_POST["cuit"];
        $origen = $_POST["origen"];
        $destino = $_POST["destino"];
        $fecha_carga = $_POST["fecha_carga"];
        $eta = $_POST["v_eta"];
        $this->viajeModel->crearViajeProforma($cliente, $origen, $destino, $fecha_carga, $eta);
    }

    public function guardarCarga()
    {
        $tipo_carga = $_POST["tipo_carga"];
        $peso_neto = $_POST["peso_neto"];
        $hazard = $_POST["ca_hazard"];
        $imo_class = $_POST["imo_class"];
        $reefer = $_POST["ca_reefer"];
        $temperatura = $_POST["temperatura"];
        $this->supervisorModel->guardarCarga($tipo_carga, $peso_neto, $hazard, $imo_class, $reefer, $temperatura);
    }

    public function guardarDatosEstimados()
    {
        $est_etd = $_POST["est_etd"];
        $est_eta = $_POST["est_eta"];
        $est_kilometros = $_POST["est_kilometros"];
        $est_combustible = $_POST["est_combustible"];
        $est_hazard = $_POST["est_hazard"];
        $est_reefer = $_POST["est_reefer"];
        $viaticos = $_POST["viaticos"];
        $peajes_pasajes = $_POST["peajes_pasajes"];
        $extras = $_POST["extras"];
        $fee = $_POST["fee"];
        $this->supervisorModel->guardarDatosEstimados($est_etd, $est_eta, $est_kilometros, $est_combustible, $est_hazard, $est_reefer, $viaticos, $peajes_pasajes, $extras, $fee);
    }

    public function volverAInicio()
    {
        echo $this->render->render("view/partial/headerSupervisor.mustache", $_SESSION),
        $this->render->render("view/Inicio.php");
    }
}
