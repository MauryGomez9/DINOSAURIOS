<?php
  require_once "models/dinosaurios.model.php";
  function run()
  {
      $epocaDinosaurios = obtenerEpocas();
      $selectedEst = 'ACT';
      $mode = "";
      $errores=array();
      $hasError = false;
      $modeDesc = array(
        "DSP" => "DiNOSAURIOS ",
        "INS" => "Creando Nuevo DiNOSAURIOS",
        "UPD" => "Actualizando DiNOSAURIOS ",
        "DEL" => "Eliminando DiNOSAURIOS "
      );
      $viewData = array();
      $viewData["showIdDinosaurios"] = true;
      $viewData["showBtnConfirmar"] = true;
      $viewData["readonly"] = '';
      $viewData["selectDisable"] = '';

      if (isset($_POST["xcfrt"]) && isset($_SESSION["xcfrt"]) &&  $_SESSION["xcfrt"] !== $_POST["xcfrt"]) {
          redirectWithMessage(
              "Petición Solicitada no es Válida",
              "index.php?page=dinosaurioslist"
          );
          die();
      }
      $viewData["xcfrt"] = $_SESSION["xcfrt"];
      if (isset($_POST["btnDsp"])) {
          $mode = "DSP";
          $dinosaurios = obtenerDinosauriosPorId($_POST["iddinosaurios"]);
          $selectedEst=$dinosaurios["epocaDinosaurios"];
          $viewData["showBtnConfirmar"] = false;
          $viewData["readonly"] = 'readonly';
          $viewData["selectDisable"] = 'disabled';
          mergeFullArrayTo($dinosaurios, $viewData);
          $viewData["modeDsc"] = $modeDesc[$mode] . $viewData["dscdinosaurios"];
      }
      if (isset($_POST["btnUpd"])) {
          $mode = "UPD";
          //Vamos A Cargar los datos
          $dinosaurios = obtenerDinosauriosPorId($_POST["iddinosaurios"]);
          $selectedEst=$dinosaurios["epocadinosaurios"];
          mergeFullArrayTo($dinosaurios, $viewData);
          $viewData["modeDsc"] = $modeDesc[$mode] . $viewData["dscdinosaurios"];
      }
      if (isset($_POST["btnDel"])) {
          $mode = "DEL";
          //Vamos A Cargar los datos
          $dinosaurios = obtenerDinosauriosPorId($_POST["iddinosaurios"]);
          $selectedEst=$dinosaurios["epocaDinosaurios"];
          $viewData["readonly"] = 'readonly';
          $viewData["selectDisable"] = 'disabled';
          mergeFullArrayTo($dinosaurios, $viewData);
          $viewData["modeDsc"] = $modeDesc[$mode] . $viewData["dscdinosaurios"];
      }
      if (isset($_POST["btnIns"])) {
          $mode = "INS";
          //Vamos A Cargar los datos
          $viewData["modeDsc"] = $modeDesc[$mode];
           $viewData["showIdDinosaurios"]  = false;
      }
      // if ($mode == "") {
      //     print_r($_POST);
      //     die();
      // }
      if (isset($_POST["btnConfirmar"])) {
          $mode = $_POST["mode"];
          $selectedEst = $_POST["epocadinosaurios"];
           mergeFullArrayTo($_POST, $viewData);
          switch($mode)
          {
          case 'INS':
              $viewData["showIdDinosaurios"] = false;
              $viewData["modeDsc"] = $modeDesc[$mode];
              //validaciones
              if (floatval($viewData["pesodinosaurios"]) <= 0) {
                  $errores[] = "El peso de dinosaurios no puede ser 0";
                  $hasError = true;
              }
              if (!$hasError && agregarNuevoDinosaurios(
                  $viewData["dscdinosaurios"],
                  $viewData["pesodinosaurios"],
                  $viewData["clasedinosaurios"],
                  $viewData["epocadinosaurios"]
              )
              ) {
                  redirectWithMessage(
                      "Dinosaurios Guardada Exitosamente",
                      "index.php?page=dinosaurioslist"
                  );
                  die();
              }
              break;
          case 'UPD':
              $viewData["modeDsc"] = $modeDesc[$mode] . $viewData["dscdinosaurios"];
              if (modificarDinosaurios(
                $viewData["dscdinosaurios"],
                $viewData["pesodinosaurios"],
                $viewData["clasedinosaurios"],
                $viewData["iddinosaurios"],
                $viewData["epocadinosaurios"]
              )
              ) {
                  redirectWithMessage(
                      "DiNOSAURIOS Actualizado Exitosamente",
                      "index.php?page=dinosaurioslist"
                  );
                  die();
              }
              break;
          case 'DEL':
              $viewData["modeDsc"] = $modeDesc[$mode] . $viewData["dscdinosaurios"];
              $viewData["readonly"] = 'readonly';
              $viewData["selectDisable"] = 'disabled';
              if (eliminarDinosaurios(
                  $viewData["iddinosaurios"]
              )
              ) {
                  redirectWithMessage(
                      "Dinosaurios Eliminado Exitosamente",
                      "index.php?page=dinosaurioslist"
                  );
                  die();
              }
              break;
          }
      }
      $viewData["mode"] = $mode;
      $viewData["epocaDinosaurios"] = addSelectedCmbArray($epocaDinosaurios, 'cod', $selectedEst);
      $viewData["hasErrors"] = $hasError;
      $viewData["errores"] = $errores;
      renderizar("dinosauriosform", $viewData);
  }
  run();
?>
