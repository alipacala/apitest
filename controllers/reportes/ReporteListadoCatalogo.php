<?php
require_once PROJECT_ROOT_PATH . "/fpdf/fpdf.php";

class ReporteListadoCatalogo
{
  function generarReporte($result = null, $idGrupo = null, $usuarioId = null)
  {
    [$result, $header] = $this->prepararDatos($result, $usuarioId);

    $pdf = new FPDF();
    $pdf->AddPage(null, 'A4');

    $lineHeight = 4;
    $tamanoLetra = 6;

    $pdf->SetFont('Arial', null, $tamanoLetra);

    $this->imprimirCabecera($pdf, $lineHeight, $tamanoLetra, $header);
    $this->imprimirCabeceraTabla($pdf, $lineHeight, $tamanoLetra);
    $this->imprimirProductosTabla($pdf, $lineHeight, $tamanoLetra, $result);

    $pdf->Output();
  }

  function prepararDatos($result = null, $idGrupo = null, $usuarioId = null)
  {
    $header = [];
    $header["FECHA"] = date("d/m/Y");
    $header["HORA"] = date("H:i:s");
    $header["USUARIO"] = $usuarioId;

    // buscar el nombre del grupo seleccionado
    $header["GRUPO_SELECCIONADO"] = $result[0]["nombre_grupo"];

    // mapear el nombre de los grupos, subgrupos y productos a UTF8
    foreach ($result as $key => $value) {
      $result[$key]["nombre_grupo"] = $this->aUTF8($value["nombre_grupo"]);
      $result[$key]["nombre_subgrupo"] = $this->aUTF8($value["nombre_subgrupo"]);
      $result[$key]["nombre_producto"] = $this->aUTF8($value["nombre_producto"]);
    }


    // agrupar productos por id_grupo y nombre_grupo, es decir, que el nombre_grupo sea el encabezado pero que el id_grupo sea el que agrupe

    $productosAgrupados = [];

    foreach ($result as $producto) {
      // agrupar por id_grupo e id_subgrupo, guardar el nombre del grupo y subgrupo para mostrarlo en el reporte
      $idGrupo = $producto["id_grupo"];
      $idSubgrupo = $producto["id_subgrupo"];
      $idGrupoProducto = $producto["id_grupo_producto"];

      if (!isset($productosAgrupados[$idGrupo])) {
        $productosAgrupados[$idGrupo] = [];
        $productosAgrupados[$idGrupo]["nombre_grupo"] = $producto["nombre_grupo"];
        if ($producto["id_producto"] != null) {
          $productosAgrupados[$idGrupo]["productos"] = [];
        }
      }

      if ($idGrupo == $idSubgrupo) {
        if ($producto["id_producto"] != null) {
          $productosAgrupados[$idGrupo]["productos"][$producto["id_producto"]] = $producto;
        }
        continue;
      }

      if (!isset($productosAgrupados[$idGrupo]["subgrupos"][$idSubgrupo])) {
        $productosAgrupados[$idGrupo]["subgrupos"][$idSubgrupo] = [];
        $productosAgrupados[$idGrupo]["subgrupos"][$idSubgrupo]["nombre_subgrupo"] = $producto["nombre_subgrupo"];
        $productosAgrupados[$idGrupo]["subgrupos"][$idSubgrupo]["productos"] = [];
      }

      if ($producto["id_producto"] != null and !isset($productosAgrupados[$idGrupo]["subgrupos"][$idSubgrupo]["productos"][$producto["id_producto"]])) {
        $productosAgrupados[$idGrupo]["subgrupos"][$idSubgrupo]["productos"][$producto["id_producto"]] = $producto;
      }
    }

    $result = $productosAgrupados;

    return [$result, $header];
  }

  function imprimirCabecera($pdf, $lineHeight, $tamanoLetra, $header)
  {
    $pdf->Image(PROJECT_ROOT_PATH . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "logo.png", null, null, 30, 0, "PNG");

    $pdf->Cell(0);
    $pdf->Ln();
    
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, $lineHeight, "LISTADO DE CATALOGO DE PRODUCTOS", 0, 0, "C");
    $pdf->Ln();
    $pdf->Ln();

    $pdf->SetFont('Arial', null, $tamanoLetra);
    $pdf->Cell(132, $lineHeight, "");
    $pdf->Cell(0, $lineHeight, "FECHA: " . $header["FECHA"], 0, 1);
    $pdf->Cell(132, $lineHeight, "");
    $pdf->Cell(0, $lineHeight, "HORA: " . $header["HORA"], 0, 1);
    $pdf->Ln();
  }

  function imprimirCabeceraTabla($pdf, $lineHeight, $tamanoLetra)
  {
    $pdf->SetFont('Arial', 'B', $tamanoLetra);

    $pdf->Cell(37, $lineHeight, "");
    $pdf->Cell(30, $lineHeight, "GRUPO / SUBGRUPOS", 1, 0, "C");
    $pdf->Cell(36, $lineHeight, "NOMBRE", 1, 0, "C");
    $pdf->Cell(16, $lineHeight, "PRECIO V.01", 1, 0, "C");
    $pdf->Cell(16, $lineHeight, "PRECIO V.02", 1, 0, "C");
    $pdf->Cell(16, $lineHeight, "PRECIO V.03", 1, 0, "C");

    $pdf->Ln();
  }

  function imprimirProductosTabla($pdf, $lineHeight, $tamanoLetra, $result)
  {
    foreach ($result as $grupo) {
      $pdf->SetFont('Arial', 'B', $tamanoLetra);
      $pdf->Cell(37, $lineHeight, "");
      $pdf->Cell(0, $lineHeight, $grupo["nombre_grupo"], 0, 1);

      if (isset($grupo["productos"])) {
        foreach ($grupo["productos"] as $producto) {
          $pdf->SetFont('Arial', null, $tamanoLetra);
          $pdf->Cell(37, $lineHeight, "");
          $pdf->Cell(30, $lineHeight, "");
          $pdf->Cell(36, $lineHeight, $producto["nombre_producto"]);
          $pdf->Cell(16, $lineHeight, $producto["precio_venta_01"] ?? "---", 0, 0, "R");
          $pdf->Cell(16, $lineHeight, $producto["precio_venta_02"] ?? "---", 0, 0, "R");
          $pdf->Cell(16, $lineHeight, $producto["precio_venta_03"] ?? "---", 0, 0, "R");

          $pdf->Ln();
        }
      } else {
        $pdf->SetFont('Arial', null, $tamanoLetra);
        $pdf->Cell(37, $lineHeight, "");
        $pdf->Cell(30, $lineHeight, "");
        $pdf->Cell(36, $lineHeight, "No hay productos en este grupo");
        $pdf->Ln();
      }

      if (isset($grupo["subgrupos"])) {
        foreach ($grupo["subgrupos"] as $subgrupo) {
          $pdf->SetFont('Arial', 'B', $tamanoLetra);
          $pdf->Cell(37, $lineHeight, "");
          $pdf->Cell(10, $lineHeight, "");
          $pdf->Cell(0, $lineHeight, $subgrupo["nombre_subgrupo"], 0, 1);

          if (count($subgrupo["productos"]) > 0) {
            foreach ($subgrupo["productos"] as $producto) {
              $pdf->SetFont('Arial', null, $tamanoLetra);
              $pdf->Cell(37, $lineHeight, "");
              $pdf->Cell(30, $lineHeight, "");
              $pdf->Cell(36, $lineHeight, $producto["nombre_producto"]);
              $pdf->Cell(16, $lineHeight, $producto["precio_venta_01"] ?? "---", 0, 0, "R");
              $pdf->Cell(16, $lineHeight, $producto["precio_venta_02"] ?? "---", 0, 0, "R");
              $pdf->Cell(16, $lineHeight, $producto["precio_venta_03"] ?? "---", 0, 0, "R");

              $pdf->Ln();
            }
          } else {
            $pdf->SetFont('Arial', null, $tamanoLetra);
            $pdf->Cell(37, $lineHeight, "");
            $pdf->Cell(30, $lineHeight, "");
            $pdf->Cell(36, $lineHeight, "No hay productos en este subgrupo");
            $pdf->Ln();
          }
        }
      }

      $pdf->Ln();
    }
  }

  function darFormatoMoneda($monto)
  {
    return number_format($monto, 2, '.', ',');
  }

  function aUTF8($string)
  {
    return mb_convert_encoding($string, "ISO-8859-1", "UTF-8");
  }

}

?>