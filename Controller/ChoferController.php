<?php


class ChoferController
{
    private $render;
    private $usuarioModel;
    private $viajeModel;
    private $gpsModel;
    public function __construct($render,$usuarioModel,$viajeModel,$gpsModel)
    {
        $this->render = $render;
        $this->usuarioModel = $usuarioModel;
        $this->viajeModel = $viajeModel;
        $this->gpsModel = $gpsModel;
    }

    public function mostrarViaje()
    {
        $viaje["viaje"]=$this->viajeModel->mostrarViaje($_SESSION["dni"]);

        echo  $this->render->render("view/partial/headerChofer.mustache",$_SESSION),
              $this->render->render("view/MiViaje.php",$viaje["viaje"]),
        print_r($viaje);
    }

    public function enviarPosicionGps()
    {
        $viaje["viaje"]=$this->viajeModel->mostrarViaje($_SESSION["dni"]);
        $this->gpsModel->guardarPosicionActual($_POST["latitud"],$_POST["longitud"]);
        $la = $_POST["latitud"];
        $lo = $_POST["longitud"];
        echo  $this->render->render("view/partial/headerChofer.mustache",$_SESSION),
        $this->render->render("view/MiViaje.php",$viaje);

    }
}