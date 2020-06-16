<?php

namespace App\Http\Controllers;

use App\Programas;
use App\Software;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DateTime;

class GruposController extends Controller {

    use \App\Traits\ApiResponser;

    // Illuminate\Support\Facades\DB;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * @OA\Info(title="Gestion Reportes", version="1",
     * @OA\Contact(
     *     email="antony.rodriguez@mimco.com.pe"
     *   )
     * )
     */

    /**
     * @OA\Post(
     *     path="/GestionReportes/public/index.php/segu_grup",
     *     tags={"Seguimiento Grupos"},
     *     summary="Permite listar los grupos para visualizar su avance.",
     *     @OA\Parameter(
     *         description="Codigo de Proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         example = 175,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Codigo Programa",
     *         in="path",
     *         name="intIdProyTarea",
     *         required=true,
     *         example = -1,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   @OA\Parameter(
     *         description="Codigo Grupo",
     *         in="path",
     *         name="intIdProyPaquete",
     *         required=true,
     *         example = -1,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Codigo Armadores",
     *         in="path",
     *         name="intIdArmadores",
     *         required=true,
     *         example = -1,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varNumeDni",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "175","intIdTipoProducto": "1","intIdProyTarea": "-1","intIdProyPaquete": "-1","intIdArmadores": "-1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El Documento de identidad ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function segu_grup(Request $request) {
        $validar = array('data' => array());
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdProyTarea' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdProyPaquete' => 'required|max:255',
            'intIdArmadores' => 'required|max:255',
            'color_busqueda' => 'required|max:255'
        ];
        date_default_timezone_set('America/Lima'); // CDT
        $current_date = date('Y-m-d');
        $this->validate($request, $regla);
        $id_proy = $request->input('intIdProy');
        $id_proy_tare = $request->input('intIdProyTarea');
        $id_proy_prod = $request->input('intIdTipoProducto');
        $id_proy_paqu = $request->input('intIdProyPaquete');
        $id_arma = $request->input('intIdArmadores');
        $color_tipo = $request->input('color_busqueda');
        if ($id_proy_tare == -1) {
            $and_proy_tare = "";
        } else {
            $and_proy_tare = " and intIdProyTarea = '$id_proy_tare' ";
        }
        if ($id_proy_paqu == -1) {
            $and_proy_paqu = "";
        } else {
            $and_proy_paqu = " and intIdProyPaquete= '$id_proy_paqu' ";
        }
        if ($id_arma == -1) {
            $and_proy_arma = "";
        } else {
            $and_proy_arma = " and proyecto_paquetes.intIdArmadores= '$id_arma' ";
        }

        $result = DB::select("select intIdProyPaquete, varCodigoPaquete,
                                        armadores.varNombArma,
                                        armadores.varApelArma,
                                    proyecto_paquetes.intIdContr,
                                    contratista.varRazCont,
                                    DATE(fecha_Inicio) as fecha_Inicio,
                                    DATE(fecha_Fin)as fecha_Fin,
                                    DATE(fecha_TerminoReal) as fecha_TerminoReal ,
                                    intIdEsta
                              from proyecto_paquetes,armadores,contratista 
                              where intIdProy = '$id_proy'   
                                    and intIdTipoProducto= '$id_proy_prod' 
                                    $and_proy_tare
                                    $and_proy_paqu
                                    $and_proy_arma
                                    and intIdEsta !='17'
                              and proyecto_paquetes.intIdArmadores = armadores.intIdArmadores
                              and contratista.intIdCont = proyecto_paquetes.intIdContr");

        for ($i = 0; $i < count($result); $i++) {
            $color = "";
            $colo_prio = "";
            $fech_real_tiem = "";
            $fech_tota = "";
            $fech_debe = "";
            $dife_dia = "";
            $datetime1 = "";
            $datetime2 = "";
            $debi_acab = "";
            $fech_comi = "";
            $fech_debi = "";
            $dife_espe = "";
            $progress = "";
            $rest = "";
            $progress_color = "";
            $estado = "";
            $contr = $result[$i]->varRazCont;
            $fech_inic = $result[$i]->fecha_Inicio;
            $fech_term = $result[$i]->fecha_Fin;
            $nomb_paqu = $result[$i]->varCodigoPaquete;
            $nomb_arma = $result[$i]->varNombArma;
            $apel_arma = $result[$i]->varApelArma;
            $fech_real = $result[$i]->fecha_TerminoReal;
            $esta_paqu = $result[$i]->intIdEsta;
            $id_paquete = $result[$i]->intIdProyPaquete;
            $fecha_termino_gant = date("Y-m-d", strtotime($fech_term . "+ 1 days"));
            $fecha_termino_csv = date("Y-m-d", strtotime($fech_term));
            //dd($contr,$fech_inic,$fech_term,$nomb_paqu,$nomb_arma,$apel_arma,$fech_real,$esta_paqu);
            if ($esta_paqu == "19") {
                $color = "#60ad5e"; //green
                $progress_color = "#1b5e20"; //green achurado
                $estado = "TERMINADO";
            }
            /* SI EL ESTADO ES 18 TIENE DOS OPCIONES SI LA FECHA DE INICIO ES MAYOR A LA FECHA DEL DIA SE COLOCA AZUL YA QUE NO EMPIEZA Y NO TIENE AVANCE
              SI LA FECHA INICIO ES MENOR A LA FECHA ACTUAL SE PINTA AMARILLO YA QUE PASO LA FECHA DE INICIO CON LA ACTUAL Y NO TIENE AVANCE */
            if ($esta_paqu == "18") {

                if ($fech_inic > $current_date) {
                    $color = "#1E90FF"; //blue 
                    $progress_color = "";
                    $estado = "EN ESPERA";
                } else {
                    $color = "#fbc02d"; //yellow
                    $progress_color = "#fff263"; //yellow achurado
                    $estado = "EN PROCESO";
                }
            }
            /* SI LA FECHA REAL NO ES NULA ESO SIGINICA SE REALIZA LAS COMPRACIONES */
            if ($fech_real != null || $fech_real != "") {
                /* SI LA FECHA REAL ES MENOR A LA FECHA TERMINO */
                //dd($fech_real,$fech_term);
                if ($fech_real < $fech_term) {
                    $fech_real_tiem = $fech_term;
                    $fech_term = $fech_real;
                    //$fecha_termino_gant = $fech_real;
                    $color = "#8bc34a"; //verde ligth
                    $progress_color = "#60ad5e"; //verde ligth
                    $estado = "TERMINADO ANTICIPADO";
                } else if ($fech_real > $fech_term) {
                    $fech_real_tiem = $fech_real;
                    $fech_term = $fech_term;
                    $color = "#326E37"; //verde con demora
                    $progress_color = "#60ad5e"; //verde ligth
                    $fecha_termino_gant = date("Y-m-d", strtotime($fech_real . "+ 1 days"));
                    $fecha_termino_csv = date("Y-m-d", strtotime($fech_real));
                    $estado = "TERMINADO CON DEMORA";
                } else {
                    $fech_real_tiem = $fech_real;
                }
            } else {
                if ($current_date > $fech_term) {
                    $fecha_termino_gant = date("Y-m-d", strtotime($current_date . "+ 1 days"));
                    $fecha_termino_csv = date("Y-m-d", strtotime($current_date));
                    $fech_real_tiem = $current_date;
                    $progress_color = "#c50e29"; //red
                    $estado = "ATRASADO";
                    $color = "#c50e29"; //red
                } else {
                    $fech_real_tiem = $fech_term;
                }
            }
            /* obtenemos la fecha real de termino */
            $datetime1 = new DateTime($fech_real_tiem);
            /* obtenemos la fecha de inicio */
            $datetime2 = new DateTime($fech_inic);
            /* obtenemos la diferencia entre la fecha de inicio y la fecha de termino */
            $fech_tota = $datetime2->diff($datetime1);
            //dd($fech_tota);
            /* obtenemos el formato del dia */
            $dife_dia = $fech_tota->format('%a');
            //dd($dife_dia);
            $dife_dia = $dife_dia + 1;
            //dd($dife_dia);

            $debi_acab = new DateTime($fech_term);
            $fech_comi = new DateTime($fech_inic);
            $fech_debi = $fech_comi->diff($debi_acab);
            $dife_espe = $fech_debi->format('%a');
            $dife_espe = $dife_espe + 1;
            //dd($dife_espe);
            //dd($datetime1,$datetime2,$fech_tota,$dife_dia,$debi_acab,$fech_comi,$fech_debi,$dife_espe);
            //dd($dife_dia,$dife_espe);
            if ($dife_espe >= $dife_dia) {

                $progress = "";
                $progress_color = "";
            } else {
                $rest = $dife_espe / $dife_dia;
                //dd($rest);
                // $progress = floatval($rest) * 0.010;
                $progress = floatval($rest);
                $progress = number_format($progress, 2);
                // $progress = $progress + 0.05;
                $progress = $progress;
            }
            //dd($color_tipo);
            if ($color_tipo === "1") {
                $validar['data'][] = array(
                    'id' => $id_paquete,
                    'contratistas' => mb_strtoupper(trim($contr), 'UTF-8'),
                    'start_date' => $fech_inic,
                    'debi_fina' => $fech_term,
                    'end_date' => $fecha_termino_gant,
                    'Fecha_final' => $fecha_termino_csv,
                    'color' => $color,
                    'nomb_paqu' => mb_strtoupper(trim($nomb_paqu), 'UTF-8'),
                    //'text' => mb_strtoupper(trim($nomb_arma), 'UTF-8') . ' ' . mb_strtoupper(trim($apel_arma), 'UTF-8'),
                    'text' => '',
                    'diferencia_total' => $dife_dia,
                    'diferencia_espe' => $dife_espe,
                    'progress' => $progress,
                    'progressColor' => $progress_color,
                    'Estaod' => $estado
                );
            } else if ($color === $color_tipo) {
                $validar['data'][] = array(
                    'id' => $id_paquete,
                    'contratistas' => mb_strtoupper(trim($contr), 'UTF-8'),
                    'start_date' => $fech_inic,
                    'debi_fina' => $fech_term,
                    'end_date' => $fecha_termino_gant,
                    'Fecha_final' => $fecha_termino_csv,
                    'color' => $color,
                    'nomb_paqu' => mb_strtoupper(trim($nomb_paqu), 'UTF-8'),
                    //'text' => mb_strtoupper(trim($nomb_arma), 'UTF-8') . ' ' . mb_strtoupper(trim($apel_arma), 'UTF-8'),
                    'text' => '',
                    'diferencia_total' => $dife_dia,
                    'diferencia_espe' => $dife_espe,
                    'progress' => $progress,
                    'progressColor' => $progress_color,
                    'Estaod' => $estado
                );
            }
            //dd($progress);
            //dd($validar);
        }
        return $this->successResponse($validar);
    }

    //listar usuario por (DNI, usuario . nombre , apellido )
    public function list_prog() {
        // $prog = Programas::get(['intIdProg','varCodiProg','varNombProg','varRutaProg','varEstaProg','acti_usua','varPublProg']);
        $prog = Programas::join('intdProg', 'varNombSoft', 'varNombProg', 'varPadrProg');

        return $this->successResponse($prog);
    }

}
