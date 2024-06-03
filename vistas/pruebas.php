<?php

  include 'header.php';

?>
   <script>
  

//export default generatePDFPedro
</script>
   
    <div id="resultado"></div>
    <div id="alertaResultado"></div> 
<div class="container-fluid">

 <h3 class="text-center">Protocolos de prueba</h3>

     
<nav class="navbar navbar-light navbar-dark bg-white">
   <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaPrueba">
          Nueva Prueba
        </button>
    

        <!-- Modal -->
        <div class="modal fade" id="nuevaPrueba" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
            <form method="POST" action="../control/registro_pruebas.php" id="formPruebas">
            <div class="modal-content">
              <div class="modal-header text-center">
                <h4 class="modal-title">NUEVO PROTOCOLO DE PRUEBA</h4>
                <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              
                <div class="row modal-body mx-4">
                <div class="col-md-4">
                    <div class="md-form mb-2">
                            <i class="grey-text">Cliente:</i>
                            <input type="text" name="cliente" class="form-control" placeholder="Cliente" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="md-form mb-2">
                            <i class="grey-text">Datos Tecnicos</i>
                            <input type="text" class="form-control" name="datos" placeholder="Datos Tecnicos" required>
                        </div>
                        </div>
                        <div class="col-md-4">
                        <div class="md-form mb-2">
                            <i class="grey-text">Fecha</i>
                            <input type="date" class="form-control" name="fecha" placeholder="Marca" required>
                        </div>
                        </div>
                </div>
                <div class="row modal-body mx-4">
                    <div class="col-md-12 col-lg-4">
                        <div class="md-form mb-2">
                            <i class="grey-text">Potencia:</i>
                            <div class="input-group">
                                <input type="number" class="form-control" name="potencia" placeholder="Potencia" required>
                                <select name="unipotencia" class="form-select col-5">
                                    <option>Kva</option>
                                    <option>Va</option>
                                    <option>Kw</option>
                                    <option>W</option>
                                </select>
                            </div>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">V1:</i>
                            <div class="input-group">
                                <input type="number" class="form-control" name="v1" placeholder="V1" required>
                                <span class="input-group-text">
                                    V.
                                </span>
                            </div>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">V2:</i>
                            <div class="input-group">
                                <input type="number" class="form-control" name="v2" placeholder="V2" required>
                                <span class="input-group-text">
                                    V.
                                </span>
                            </div>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">L1:</i>
                            <div class="input-group">
                                <input type="number" class="form-control" name="l1" placeholder="L1" required>
                                <span class="input-group-text">
                                    A.
                                </span>
                            </div>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">L2:</i>
                            <div class="input-group">
                                <input type="number" class="form-control" name="l2" placeholder="L2" required>
                                <span class="input-group-text">
                                    A.
                                </span>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="md-form mb-2">
                            <i class="grey-text">Fases:</i>
                            <input type="number" class="form-control" name="fases" placeholder="Fases" required>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">Frecuencia:</i>
                            <div class="input-group">
                                <input type="number" class="form-control" placeholder="Frecuencia" name="frecuencia" required>
                                <span class="input-group-text">
                                    HZ
                                </span>
                            </div>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">Conexión:</i>
                            <input type="text" class="form-control" name="conexion" placeholder="Conexion" required>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">Grupo:</i>
                            <input type="text" class="form-control" name="grupo" placeholder="Grupo" required>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">Altitud:</i>
                            <div class="input-group">
                                <input type="number" class="form-control" name="altitud" placeholder="Altitud" required>
                                <span class="input-group-text">
                                    m.s.n.m
                                </span>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="md-form mb-2">
                            <i class="grey-text">Marca:</i>
                            <input type="text" class="form-control" name="marca" placeholder="Marca" required>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">Numero de Serie:</i>
                            <input type="text" class="form-control" name="serie" placeholder="numero de serie" required>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">Año de Fabricación:</i>
                            <select name="fabricacion" class="form-select" required>
                                <?php
                                    $anio_actual = date("Y");
                                    for ($i = 2020; $i <= $anio_actual + 100; $i++) {
                                        echo "<option>$i</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">Norma:</i>
                            <input type="text" class="form-control" name="norma" placeholder="Norma" required>
                        </div>
                    </div>
                </div>

                <div class="row modal-body mx-4">
                    <div class="col-md-12">
                        PRUEBA DE RELACION DE TRANSFORMACION:
                    </div>
                </div>
                <div class="row modal-body mx-4">
                    <div class="col-md-12 col-lg-3">
                        <div class="md-form mb-2">
                            <i class="grey-text">Relacion teorica:</i>
                            <input type="number" class="form-control" name="rela_teo" placeholder="Relacion teorica" required>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-3">
                        <div class="md-form mb-2">
                            <i class="grey-text">U - V:</i>
                            <input type="text" class="form-control" name="uv1" placeholder="U - V" required>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-3">
                        <div class="md-form mb-2">
                            <i class="grey-text">U - V:</i>
                            <input type="text" class="form-control" name="uv2" placeholder="U - V" required>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-3">
                        <div class="md-form mb-2">
                            <i class="grey-text">U - V:</i>
                            <input type="text" class="form-control" name="uv3" placeholder="U - V" required>
                        </div>
                    </div>
                </div>


                <div class="row modal-body mx-4">
                    <div class="col-md-12">
                        PRUEBA DE VACIO:
                    </div>
                </div>
                <div class="row modal-body mx-4">
                    <div class="col-md-12 col-lg-6">
                        <div class="md-form mb-2">
                            <i class="grey-text">TENSION u-v:</i>
                            <input type="number" class="form-control" name="tensionu_v" placeholder="TENSION u-v" required>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">TENSION v-w:</i>
                            <input type="number" class="form-control" name="tensionv_w" placeholder="TENSION v-w" required>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">TENSION w-u:</i>
                            <input type="number" class="form-control" name="tensionw_u" placeholder="TENSION w-u" required>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="md-form mb-2">
                            <i class="grey-text">INTENSIDAD u-v:</i>
                            <input type="number" class="form-control" placeholder="INTENSIDAD u-v" name="intensidadu_v" required>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">INTENSIDAD v-w:</i>
                            <input type="number" class="form-control" placeholder="INTENSIDAD v-w" name="intensidadv_w" required>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">INTENSIDAD w-u:</i>
                            <input type="number" class="form-control" placeholder="INTENSIDAD w-u" name="intensidadw_u" required>
                        </div>
                    </div>
                </div>


                <div class="row modal-body mx-4">
                    <div class="col-md-12">
                        MEDIDA DE LA RESISTENCIA DE AISLAMIENTO:
                    </div>
                </div>
                <div class="row modal-body mx-4">
                    <div class="col-md-12 col-lg-4">
                        <div class="md-form mb-2">
                            <i class="grey-text">AT-BT:</i>
                            <div class="input-group">
                                <input type="text" class="form-control" name="at_bt" placeholder="AT-BT:" required>
                                <select name="at_bt_und" class="form-select col-5">
                                    <option>G-ohm</option>
                                    <option>M-ohm</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="md-form mb-2">
                            <i class="grey-text">AT-M:</i>
                            <div class="input-group">
                                <input type="text" class="form-control" name="at_m" placeholder="AT-M:" required>
                                <select name="at_m_und" class="form-select col-5">
                                    <option>G-ohm</option>
                                    <option>M-ohm</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="md-form mb-2">
                            <i class="grey-text">BT-M:</i>
                            <div class="input-group">
                                <input type="text" class="form-control" name="bt_m" placeholder="BT-M:" required>
                                <select name="bt_m_und" class="form-select col-5">
                                    <option>G-ohm</option>
                                    <option>M-ohm</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row modal-body mx-4">
                    <div class="col-md-12">
                        MEDIDA DEL ESPESOR DE PINTURA:
                    </div>
                </div>
                <div class="row modal-body mx-4">
                    <div class="col-md-12 col-lg-4">
                        <div class="md-form mb-2">
                            <i class="grey-text">Valor minimo:</i>
                            <input type="number" class="form-control" name="minimo" placeholder="Valor minimo" required>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="md-form mb-2">
                            <i class="grey-text">Valor medido:</i>
                            <input type="number" class="form-control" name="medido" placeholder="Valor medido">
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="md-form mb-2">
                            <i class="grey-text">Resultado:</i>
                            <input type="text" class="form-control" name="resultado" placeholder="Resultado" required>
                        </div>
                    </div>
                </div>

                <div class="row modal-body mx-4">
                    <div class="col-md-12">
                        PRUEBA DE CORTO CIRCUITO
                    </div>
                </div>
                <div class="row modal-body mx-4">
                    <div class="col-md-12 col-lg-6">
                        <div class="md-form mb-2">
                            <i class="grey-text">INTENSIDAD</i>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">Lectura:</i>
                            <input type="number" class="form-control" name="int_lectura" placeholder="Lectura" required>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">Valor:</i>
                            <input type="number" class="form-control" name="int_valor" placeholder="Valor" required>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="md-form mb-2">
                            <i class="grey-text">TENSION</i>
                        </div>
                        <div class="md-form mb-2">
                            <i class="grey-text">Lectura:</i>
                            <input type="number" class="form-control" name="ten_lectura" placeholder="Lectura" required>
                        </div>
                    </div>
                </div>

                <div class="row modal-body mx-4">
                    <div class="col-md-12 form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="checkresis" id="checkResis" onchange="activarResis()">
                        <label class="form-check-label" for="checkResis">Prueba de resistencia</label>
                    </div>
                </div>
                <div id="pruebaResis">
                </div>
                    
                <div class="modal-footer justify-content-center">
                    <button  type="button" id="btnPruebas" onclick="registrarPrueba()" class="btn btn-primary">Guardar</button>
                </div>
                
            </div>
            </form>
          </div>
        </div>
        
  <form class="form-inline">
    <input class="form-control mr-sm-2" type="search" placeholder="Buscar" id="campo" aria-label="Search">
  </form>
</nav>
     
  <table class="table table-striped text-center table-hover table-borderless table-sm">
  <label for="num_registros">Mostrar: </label>

  <select name="num_registros" id="num_registros">
      <option value="10">10</option>
      <option value="25">25</option>
      <option value="50">50</option>
  </select>

  <label for="num_registros">registros</label>
 <thead>
    <tr>
      <th scope="col" scope="row">Razon social</th>
      <th scope="col">Fecha</th>
      <th scope="col">Descargar</th>
    </tr>
  </thead>
  <tbody class="table-group-divider" id="contenido">
  </tbody>
</table>


    <div>
        <label for="" id="lbl-total"></label>

        <div id="nav-paginacion"></div>

        <input type="hidden" id="pagina" value="1">
        <input type="hidden" id="orderCol" value="0">
        <input type="hidden" id="orderType" value="asc">
        <input type="hidden" id="queListado" value="productos">
    </div>
   </div>

   <script src='../static/js/ajax_listado_pruebas.js?v=2.2' async></script>
   
    <?php

        include 'footer.php';

    ?>