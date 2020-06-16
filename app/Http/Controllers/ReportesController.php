<?php

namespace App\Http\Controllers;

use App\Proyectozona;
use App\Proyecto;
use App\AsignarEtapa;
use App\Rutaproyecto;
use App\Detalleruta;
use App\Elemento;
use App\Etapa;
use App\TipoEtapas;
use App\Contratista;
use App\Componente;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DateTime;

class ReportesController extends Controller {

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
     * @OA\Post(
     *     path="/GestionReportes/public/index.php/list_ot_o_todas_ot_vers2",
     *     tags={"Reportes"},
     *     summary="lista ot o todos las ot",
     *     @OA\Parameter(
     *         description="ingrese el id proyectos , puede ser uno o mas",
     *         in="path",
     *         name="v_varintIdProy",
     *        example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="ingrese el id del tipo producto",
     *         in="path",
     *         name="v_intIdTipoProducto",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="ingrese el id ingrese la unidad",
     *         in="path",
     *         name="v_unidad",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="ingrese la opcion",
     *         in="path",
     *         name="v_opcion",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

      @OA\Parameter(
     *         description="ingrese la fecha de inicio",
     *         in="path",
     *         name="v_fech_inic",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 

      @OA\Parameter(
     *         description="ingrese la fecha de final",
     *         in="path",
     *         name="v_fech_fina",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="v_varintIdProy",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="v_intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="v_unidad",
     *                     type="string"
     *                 ) ,
     *                 
     *                @OA\Property(
     *                     property="v_opcion",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="v_fech_inic",
     *                     type="string"
     *                 ) ,
     * 
     *                @OA\Property(
     *                     property="v_fech_fina",
     *                     type="string"
     *                 ) ,
     *                 example={"v_varintIdProy": "126","v_intIdTipoProducto":"1","v_unidad":"1","v_opcion":"1","v_fech_inic":"","v_fech_fina":""}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_ot_o_todas_ot_vers2(Request $request) {

        $regla = [
            'v_varintIdProy' => 'required|max:255',
            'v_intIdTipoProducto' => 'required',
            'v_unidad' => 'required',
            //'v_flgRango'=>'required',
            'v_opcion' => 'required',
            //  'm_fech_inic' => 'required|max:255',
            //  'm_fech_fina' => 'required|max:255',
            'v_TipoGrupo' => 'required'
        ];
        $this->validate($request, $regla);

        $v_varintIdProy = trim($request->input('v_varintIdProy'), ',');
        // dd($v_varintIdProy);
        $v_intIdTipoProducto = (int) $request->input('v_intIdTipoProducto');
        $v_fech_inic = "";
        $v_fech_fina = "";
        $v_unidad = $request->input('v_unidad');

        // $v_flgRango=$request->input('v_flgRango');
        $v_opcion = (int) $request->input('v_opcion');
        $v_TipoGrupo = (int) $request->input('v_TipoGrupo');

        if ($request->input('v_fech_inic') == '') {
            $v_fech_inic = '';
        } else {
            $v_fech_inic = $request->input('v_fech_inic');
        }
        if ($request->input('v_fech_fina') == '') {
            $v_fech_fina = '';
        } else {
            $v_fech_fina = $request->input('v_fech_fina');
        }


        if ($v_fech_inic == '' && $v_fech_fina == '') {
            $v_flgRango = 0;
        } else {
            $v_flgRango = 1;
        }
        //  dd($v_varintIdProy,$v_intIdTipoProducto,$v_unidad,$v_flgRango,$v_fech_inic,$v_fech_fina,$v_opcion,$v_TipoGrupo);

        if ($v_intIdTipoProducto === 1) {
            DB::select('CALL sp_repo_resumenOT(?,?,?,?,?,?,?,?)', array(
                $v_varintIdProy,
                $v_intIdTipoProducto,
                $v_unidad,
                $v_flgRango,
                $v_fech_inic,
                $v_fech_fina,
                $v_opcion,
                $v_TipoGrupo
            ));

            $respuesta = DB::select('SELECT * FROM Temp_ResumenOT ORDER BY nombProy');
            DB::select('DROP TABLE IF EXISTS Temp_ResumenOT');


            return $this->successResponse($respuesta);
        }
        if ($v_intIdTipoProducto === 2) {
            // dd($v_varintIdProy,$v_intIdTipoProducto,$v_unidad,$v_flgRango,$v_fech_inic,$v_fech_fina,$v_opcion);
            DB::select('CALL sp_repo_resumenOTCompo(?,?,?,?,?,?,?,?)', array(
                $v_varintIdProy,
                $v_intIdTipoProducto,
                $v_unidad,
                $v_flgRango,
                $v_fech_inic,
                $v_fech_fina,
                $v_opcion,
                $v_TipoGrupo
            ));

            $respuesta = DB::select('SELECT * FROM Temp_ResumenOT ORDER BY nombProy');
            DB::select('DROP TABLE IF EXISTS Temp_ResumenOT');

            return $this->successResponse($respuesta);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionReportes/public/index.php/list_ot_o_todas_ot",
     *     tags={"Reportes"},
     *     summary="lista ot o todos las ot",
     *     @OA\Parameter(
     *         description="ingrese el id proyectos , puede ser uno o mas",
     *         in="path",
     *         name="m_intIdProy",
     *        example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="ingrese el id del tipo producto",
     *         in="path",
     *         name="m_intIdTipoProducto",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),



      @OA\Parameter(
     *         description="ingrese la fecha de inicio",
     *         in="path",
     *         name="v_fech_inic",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 

      @OA\Parameter(
     *         description="ingrese la fecha de final",
     *         in="path",
     *         name="v_fech_fina",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
      @OA\Parameter(
     *         description="ingrese la unidad",
     *         in="path",
     *         name="m_unidad",
     *        example="1",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="m_intIdProy",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="m_intIdTipoProducto",
     *                     type="string"
     *                 ) ,

     *            @OA\Property(
     *                     property="m_unidad",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="v_fech_inic",
     *                     type="string"
     *                 ) ,
     * 
     *                @OA\Property(
     *                     property="v_fech_fina",
     *                     type="string"
     *                 ) ,
     *                 example={"m_intIdProy": "126","m_intIdTipoProducto":"1","v_fech_inic":"","v_fech_fina":"","m_unidad":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listar la OT o todas las OT"
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_ot_o_todas_ot(Request $request) {

        $regla = [
            'm_intIdProy' => 'required',
            'm_intIdTipoProducto' => 'required',
            //  'm_fech_inic' => 'required|max:255',
            //  'm_fech_fina' => 'required|max:255',
            'm_unidad' => 'required'
        ];
        $this->validate($request, $regla);

        $m_intIdProy = (int) $request->input('m_intIdProy');
        $m_intIdTipoProducto = (int) $request->input('m_intIdTipoProducto');
        $m_fech_inic = "";
        $m_fech_fina = "";


        $m_unidad = $request->input('m_unidad');

        if ($request->input('m_fech_inic') == '') {
            $m_fech_inic = '';
        } else {
            $m_fech_inic = $request->input('m_fech_inic');
        }
        if ($request->input('m_fech_fina') == '') {
            $m_fech_fina = "";
        } else {
            $m_fech_fina = $request->input('m_fech_fina');
        }
        // dd($m_intIdProy,$m_intIdTipoProducto,$m_fech_fina,$m_fech_inic,$m_unidad);

        DB::select('CALL sp_Repo_ot2(?,?,?,?,?)', array(
            $m_intIdProy,
            $m_intIdTipoProducto,
            $m_fech_inic,
            $m_fech_fina,
            $m_unidad
        ));

        $respuesta = DB::select('select * from x2');
        DB::select('DROP TABLE IF EXISTS x2');
        DB::select('DROP TABLE IF EXISTS x1');
        return $this->successResponse($respuesta);
    }

    /**
     * @OA\Post(
     *     path="/GestionReportes/public/index.php/repo_zona_vers2",
     *     tags={"Reportes"},
     *     summary="Reporte de zona",
     *     @OA\Parameter(
     *         description="ingrese el id proyectos , puede ser uno o mas",
     *         in="path",
     *         name="v_intIdProy",
     *        example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="ingrese el id del tipo producto",
     *         in="path",
     *         name="v_intIdTipoProducto",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),



      @OA\Parameter(
     *         description="ingrese la fecha de inicio",
     *         in="path",
     *         name="v_fech_inic",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 

      @OA\Parameter(
     *         description="ingrese la fecha de final",
     *         in="path",
     *         name="v_fech_fina",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
      @OA\Parameter(
     *         description="ingrese la unidad",
     *         in="path",
     *         name="v_unidad",
     *        example="1",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="ingrese la opcion",
     *         in="path",
     *         name="v_opcion",
     *        example="1",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="v_intIdProy",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="v_intIdTipoProducto",
     *                     type="string"
     *                 ) ,

     *            @OA\Property(
     *                     property="v_unidad",
     *                     type="string"
     *                 ) ,
     *            @OA\Property(
     *                     property="v_opcion",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="v_fech_inic",
     *                     type="string"
     *                 ) ,
     * 
     *                @OA\Property(
     *                     property="v_fech_fina",
     *                     type="string"
     *                 ) ,
     *                 example={"v_intIdProy": "126","v_intIdTipoProducto":"1","v_opcion":"1","v_unidad":"1","v_fech_inic":"","v_fech_fina":"","m_unidad":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listar la OT o todas las OT"
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function repo_zona_vers2(Request $request) {

        $regla = [
            'v_intIdProy' => 'required|max:255',
            'v_intIdTipoProducto' => 'required|max:255',
            'v_unidad' => 'required|max:255',
            'v_opcion' => 'required|max:255',
            //    'm_fech_inic' => 'required|max:255',
            // 'm_fech_fina' => 'required|max:255',
            'v_TipoGrupo' => 'required'
        ];

        $this->validate($request, $regla);

        $v_intIdProy = (int) $request->input('v_intIdProy');
        $v_intIdTipoProducto = (int) $request->input('v_intIdTipoProducto');
        $v_unidad = $request->input('v_unidad');
        $v_opcion = (int) $request->input('v_opcion');
        $v_TipoGrupo = (int) $request->input('v_TipoGrupo');
        $v_fech_inic = "";
        $v_fech_fina = "";


        if ($request->input('v_fech_inic') == '') {
            $v_fech_inic = '';
        } else {
            $v_fech_inic = $request->input('v_fech_inic');
        }
        if ($request->input('v_fech_fina') == '') {
            $v_fech_fina = "";
        } else {
            $v_fech_fina = $request->input('v_fech_fina');
        }


        if ($v_fech_inic == "" && $v_fech_fina == "") {
            $v_flgRango = 0;
        } else {
            $v_flgRango = 1;
        }


        if ($v_intIdTipoProducto === 1) {
            DB::select('CALL sp_repo_resumenzona(?,?,?,?,?,?,?,?)', array(
                $v_intIdProy,
                $v_intIdTipoProducto,
                $v_unidad,
                $v_flgRango,
                $v_fech_inic,
                $v_fech_fina,
                $v_opcion,
                $v_TipoGrupo
            ));
            $respuesta = DB::select('SELECT * FROM Temp_ResumenOT ORDER BY nombProy ;');
            DB::select('DROP TABLE Temp_ResumenOT');

            return $this->successResponse($respuesta);
        }

        if ($v_intIdTipoProducto === 2) {

            DB::select('CALL sp_repo_resumenzonaCompo(?,?,?,?,?,?,?,?)', array(
                $v_intIdProy,
                $v_intIdTipoProducto,
                $v_unidad,
                $v_flgRango,
                $v_fech_inic,
                $v_fech_fina,
                $v_opcion,
                $v_TipoGrupo
            ));
            $respuesta = DB::select('SELECT * FROM Temp_ResumenOT ORDER BY nombProy ;');
            DB::select('DROP TABLE Temp_ResumenOT');

            return $this->successResponse($respuesta);
        }
        //
    }

    /**
     * @OA\Post(
     *     path="/GestionReportes/public/index.php/repo_zona",
     *     tags={"Reportes"},
     *     summary="Reporte de zona",
     *     @OA\Parameter(
     *         description="ingrese el id proyectos , puede ser uno o mas",
     *         in="path",
     *         name="m_intIdProy",
     *        example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="ingrese el id del tipo producto",
     *         in="path",
     *         name="m_intIdTipoProducto",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),



      @OA\Parameter(
     *         description="ingrese la fecha de inicio",
     *         in="path",
     *         name="m_fech_inic",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 

      @OA\Parameter(
     *         description="ingrese la fecha de final",
     *         in="path",
     *         name="m_fech_fina",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
      @OA\Parameter(
     *         description="ingrese la unidad",
     *         in="path",
     *         name="m_unidad",
     *        example="1",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="m_intIdProy",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="m_intIdTipoProducto",
     *                     type="string"
     *                 ) ,

     *            @OA\Property(
     *                     property="m_unidad",
     *                     type="string"
     *                 ) ,
     *            
     *                @OA\Property(
     *                     property="m_fech_inic",
     *                     type="string"
     *                 ) ,
     * 
     *                @OA\Property(
     *                     property="m_fech_fina",
     *                     type="string"
     *                 ) ,
     *                 example={"m_intIdProy": "126","m_intIdTipoProducto":"1","m_unidad":"1","m_fech_inic":"","m_fech_fina":""}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listar la OT o todas las OT"
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function repo_zona(Request $request) {

        $regla = [
            'm_intIdProy' => 'required|max:255',
            'm_intIdTipoProducto' => 'required|max:255',
            //    'm_fech_inic' => 'required|max:255',
            // 'm_fech_fina' => 'required|max:255',
            'm_unidad' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $m_intIdProy = (int) $request->input('m_intIdProy');
        $m_intIdTipoProducto = (int) $request->input('m_intIdTipoProducto');
        $m_fech_inic = "";
        $m_fech_fina = "";
        $m_unidad = $request->input('m_unidad');


        if ($request->input('m_fech_inic') == '') {
            $m_fech_inic = '';
        } else {
            $m_fech_inic = $request->input('m_fech_inic');
        }
        if ($request->input('m_fech_fina') == '') {
            $m_fech_fina = "";
        } else {
            $m_fech_fina = $request->input('m_fech_fina');
        }


        DB::select('CALL sp_Repo_zona2(?,?,?,?,?)', array(
            $m_intIdProy,
            $m_intIdTipoProducto,
            $m_fech_inic,
            $m_fech_fina,
            $m_unidad
        ));
        $respuesta = DB::select('select * from x4');
        DB::select('DROP TABLE IF EXISTS x3');
        DB::select('DROP TABLE IF EXISTS x4');
        return $this->successResponse($respuesta);
    }

    /**
     * @OA\Post(
     *     path="/GestionReportes/public/index.php/repo_prog",
     *     tags={"Reportes"},
     *     summary="Reporte de programa",
     *     @OA\Parameter(
     *         description="ingrese el id proyectos , puede ser uno o mas",
     *         in="path",
     *         name="v_intIdProy",
     *        example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="ingrese el id del tipo producto",
     *         in="path",
     *         name="v_intIdTipoProducto",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="ingrese el id del proyecto",
     *         in="path",
     *         name="v_intIdProyZona",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),



      @OA\Parameter(
     *         description="ingrese la fecha de inicio",
     *         in="path",
     *         name="v_fech_inic",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 

      @OA\Parameter(
     *         description="ingrese la fecha de final",
     *         in="path",
     *         name="v_fech_fina",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
      @OA\Parameter(
     *         description="ingrese la unidad",
     *         in="path",
     *         name="v_unidad",
     *        example="1",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

      @OA\Parameter(
     *         description="ingrese la opcion",
     *         in="path",
     *         name="v_opcion",
     *        example="1",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="v_intIdProy",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="v_intIdTipoProducto",
     *                     type="string"
     *                 ) ,

     *            @OA\Property(
     *                     property="v_unidad",
     *                     type="string"
     *                 ) ,
     *   
     *            @OA\Property(
     *                     property="v_opcion",
     *                     type="string"
     *                 ) ,
     *            
     *                @OA\Property(
     *                     property="v_fech_inic",
     *                     type="string"
     *                 ) ,
     * 
     *                @OA\Property(
     *                     property="v_fech_fina",
     *                     type="string"
     *                 ) ,
     *                 example={"v_intIdProy": "126","v_intIdTipoProducto":"1","v_unidad":"1","v_fech_inic":"","v_fech_fina":"","v_opcion":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reporte de programas"
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function repo_prog(Request $request) {

        $regla = [
            'v_intIdProy' => 'required|max:255',
            'v_intIdTipoProducto' => 'required|max:255',
            'v_intIdProyZona' => 'required|max:255',
            //'m_fech_inic' => 'required|max:255',
            // 'm_fech_fina' => 'required|max:255',
            'v_unidad' => 'required|max:255',
            'v_opcion' => 'required|max:255',
            'v_TipoGrupo' => 'required'
        ];
        $this->validate($request, $regla);

        $v_intIdProy = (int) $request->input('v_intIdProy');
        $v_intIdTipoProducto = (int) $request->input('v_intIdTipoProducto');
        $v_intIdProyZona = (int) $request->input('v_intIdProyZona');
        $v_fech_inic = "";
        $v_fech_fina = "";
        $v_unidad = $request->input('v_unidad');
        $v_opcion = $request->input('v_opcion');
        $v_TipoGrupo = $request->input('v_TipoGrupo');

        if ($request->input('v_fech_inic') == '') {
            $v_fech_inic = '';
        } else {
            $v_fech_inic = $request->input('v_fech_inic');
        }
        if ($request->input('v_fech_fina') == '') {
            $v_fech_fina = "";
        } else {
            $v_fech_fina = $request->input('v_fech_fina');
        }
        // dd($m_intIdProy,$m_intIdTipoProducto,$m_fech_fina,$m_fech_inic,$m_unidad);

        if ($v_fech_inic == "" && $v_fech_fina == "") {
            $v_flgRango = 0;
        } else {
            $v_flgRango = 1;
        }

        if ($v_intIdTipoProducto === 1) {
            DB::select('CALL sp_repo_resumenprograma(?,?,?,?,?,?,?,?,?)', array(
                $v_intIdProy,
                $v_intIdTipoProducto,
                $v_intIdProyZona,
                $v_unidad,
                $v_flgRango,
                $v_fech_inic,
                $v_fech_fina,
                $v_opcion,
                $v_TipoGrupo
            ));
            $respuesta = DB::select('SELECT * FROM Temp_ResumenOT ORDER BY nombProy');
            DB::select('DROP TABLE Temp_ResumenOT');

            return $this->successResponse($respuesta);
        }

        if ($v_intIdTipoProducto === 2) {
            DB::select('CALL sp_repo_resumenprogramaCompo(?,?,?,?,?,?,?,?,?)', array(
                $v_intIdProy,
                $v_intIdTipoProducto,
                $v_intIdProyZona,
                $v_unidad,
                $v_flgRango,
                $v_fech_inic,
                $v_fech_fina,
                $v_opcion,
                $v_TipoGrupo
            ));
            $respuesta = DB::select('SELECT * FROM Temp_ResumenOT ORDER BY nombProy');
            DB::select('DROP TABLE Temp_ResumenOT');

            return $this->successResponse($respuesta);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionReportes/public/index.php/repo_grup",
     *     tags={"Reportes"},
     *     summary="Reporte de programa",
     *     @OA\Parameter(
     *         description="ingrese el id proyectos , puede ser uno o mas",
     *         in="path",
     *         name="v_intIdProy",
     *        example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="ingrese el id del tipo producto",
     *         in="path",
     *         name="v_intIdTipoProducto",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="ingrese el id del proyecto zona",
     *         in="path",
     *         name="v_intIdProyZona",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="ingrese el id del proyecto tarea",
     *         in="path",
     *         name="v_intIdProyTarea",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 




      @OA\Parameter(
     *         description="ingrese la fecha de inicio",
     *         in="path",
     *         name="v_fech_inic",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 

      @OA\Parameter(
     *         description="ingrese la fecha de final",
     *         in="path",
     *         name="v_fech_fina",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
      @OA\Parameter(
     *         description="ingrese la unidad",
     *         in="path",
     *         name="v_unidad",
     *        example="1",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

      @OA\Parameter(
     *         description="ingrese la opcion",
     *         in="path",
     *         name="v_opcion",
     *        example="1",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="v_intIdProy",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="v_intIdTipoProducto",
     *                     type="string"
     *                 ) ,
      @OA\Property(
     *                     property="v_intIdProyZona",
     *                     type="string"
     *                 ) ,
      @OA\Property(
     *                     property="v_intIdProyTarea",
     *                     type="string"
     *                 ) ,


     *            @OA\Property(
     *                     property="v_unidad",
     *                     type="string"
     *                 ) ,
     *   
     *            @OA\Property(
     *                     property="v_opcion",
     *                     type="string"
     *                 ) ,
     *            
     *                @OA\Property(
     *                     property="v_fech_inic",
     *                     type="string"
     *                 ) ,
     * 
     *                @OA\Property(
     *                     property="v_fech_fina",
     *                     type="string"
     *                 ) ,
     *                 example={"v_intIdProy": "126","v_intIdTipoProducto":"1","v_intIdProyZona":"1","v_intIdProyTarea":"1","v_unidad":"1","v_fech_inic":"","v_fech_fina":"","v_opcion":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reporte de programas"
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function repo_grup(Request $request) {

        $regla = [
            'v_intIdProy' => 'required|max:255',
            'v_intIdTipoProducto' => 'required|max:255',
            'v_intIdProyZona' => 'required|max:255',
            'v_intIdProyTarea' => 'required|max:255',
            'v_unidad' => 'required|max:255',
            //'m_fech_inic' => 'required|max:255',
            // 'm_fech_fina' => 'required|max:255',
            'v_opcion' => 'required|max:255',
            'v_TipoGrupo' => 'required'
        ];
        $this->validate($request, $regla);

        $v_intIdProy = (int) $request->input('v_intIdProy');
        $v_intIdTipoProducto = (int) $request->input('v_intIdTipoProducto');
        $v_intIdProyZona = (int) $request->input('v_intIdProyZona');
        $v_intIdProyTarea = (int) $request->input('v_intIdProyTarea');
        $v_TipoGrupo = (int) $request->input('v_TipoGrupo');

        $v_fech_inic = "";
        $v_fech_fina = "";
        $v_unidad = $request->input('v_unidad');
        $v_opcion = (int) $request->input('v_opcion');


        if ($request->input('v_fech_inic') == '') {
            $v_fech_inic = '';
        } else {
            $v_fech_inic = $request->input('v_fech_inic');
        }
        if ($request->input('v_fech_fina') == '') {
            $v_fech_fina = "";
        } else {
            $v_fech_fina = $request->input('v_fech_fina');
        }
        // dd($m_intIdProy, $m_intIdTipoProducto, $m_fech_fina, $m_fech_inic, $m_unidad, $v_TipoGrupo);

        if ($v_fech_inic == "" && $v_fech_fina == "") {
            $v_flgRango = 0;
        } else {
            $v_flgRango = 1;
        }


        if ($v_intIdTipoProducto === 1) {
            DB::select('CALL sp_repo_resumengrupo(?,?,?,?,?,?,?,?,?,?)', array(
                $v_intIdProy,
                $v_intIdTipoProducto,
                $v_intIdProyZona,
                $v_intIdProyTarea,
                $v_unidad,
                $v_flgRango,
                $v_fech_inic,
                $v_fech_fina,
                $v_opcion,
                $v_TipoGrupo
            ));

            $respuesta = DB::select(' SELECT * FROM Temp_ResumenOT ORDER BY nombProy');
            DB::select(' DROP TABLE Temp_ResumenOT');

            return $this->successResponse($respuesta);
        }
        if ($v_intIdTipoProducto === 2) {
            DB::select('CALL sp_repo_resumengrupocompo(?,?,?,?,?,?,?,?,?,?)', array(
                $v_intIdProy,
                $v_intIdTipoProducto,
                $v_intIdProyZona,
                $v_intIdProyTarea,
                $v_unidad,
                $v_flgRango,
                $v_fech_inic,
                $v_fech_fina,
                $v_opcion,
                $v_TipoGrupo
            ));

            $respuesta = DB::select(' SELECT * FROM Temp_ResumenOT ORDER BY nombProy');
            DB::select(' DROP TABLE Temp_ResumenOT');

            return $this->successResponse($respuesta);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionReportes/public/index.php/repo_codi",
     *     tags={"Reportes"},
     *     summary="Reporte de programa",
     *     @OA\Parameter(
     *         description="ingrese el id proyectos , puede ser uno o mas",
     *         in="path",
     *         name="v_intIdProy",
     *        example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="ingrese el id del tipo producto",
     *         in="path",
     *         name="v_intIdTipoProducto",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="ingrese el id del proyecto zona",
     *         in="path",
     *         name="v_intIdProyZona",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="ingrese el id del proyecto tarea",
     *         in="path",
     *         name="v_intIdProyTarea",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 



      @OA\Parameter(
     *         description="ingrese el id del proyecto tarea",
     *         in="path",
     *         name="v_intIdProyPaquete",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 





      @OA\Parameter(
     *         description="ingrese la fecha de inicio",
     *         in="path",
     *         name="v_fech_inic",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 

      @OA\Parameter(
     *         description="ingrese la fecha de final",
     *         in="path",
     *         name="v_fech_fina",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
      @OA\Parameter(
     *         description="ingrese la unidad",
     *         in="path",
     *         name="v_unidad",
     *        example="1",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),


     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="v_intIdProy",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="v_intIdTipoProducto",
     *                     type="string"
     *                 ) ,
      @OA\Property(
     *                     property="v_intIdProyZona",
     *                     type="string"
     *                 ) ,
      @OA\Property(
     *                     property="v_intIdProyTarea",
     *                     type="string"
     *                 ) ,

      @OA\Property(
     *                     property="v_intIdProyPaquete",
     *                     type="string"
     *                 ) ,


     *            @OA\Property(
     *                     property="v_unidad",
     *                     type="string"
     *                 ) ,

     *            
     *                @OA\Property(
     *                     property="v_fech_inic",
     *                     type="string"
     *                 ) ,
     * 
     *                @OA\Property(
     *                     property="v_fech_fina",
     *                     type="string"
     *                 ) ,
     *                 example={"v_intIdProy": "126","v_intIdTipoProducto":"1","v_intIdProyZona":"1","v_intIdProyTarea":"1","v_unidad":"1","v_fech_inic":"","v_fech_fina":""}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reporte de codigo"
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function repo_codi(Request $request) {

        $regla = [
            'v_intIdProy' => 'required|max:255',
            'v_intIdTipoProducto' => 'required|max:255',
            'v_intIdProyZona' => 'required|max:255',
            'v_intIdProyTarea' => 'required|max:255',
            'v_intIdProyPaquete' => 'required|max:255',
            'v_unidad' => 'required|max:255',
            'v_opcion' => 'required|max:255',
            'v_TipoGrupo' => 'required'
        ];
        $this->validate($request, $regla);

        $v_intIdProy = (int) $request->input('v_intIdProy');
        $v_intIdTipoProducto = (int) $request->input('v_intIdTipoProducto');
        $v_intIdProyZona = (int) $request->input('v_intIdProyZona');
        $v_intIdProyTarea = (int) $request->input('v_intIdProyTarea');
        $v_intIdProyPaquete = (int) $request->input('v_intIdProyPaquete');
        $v_fech_inic = "";
        $v_fech_fina = "";
        $v_unidad = $request->input('v_unidad');
        $v_opcion = (int) $request->input('v_opcion');
        $v_TipoGrupo = (int) $request->input('v_TipoGrupo');

        if ($request->input('v_fech_inic') == '') {
            $v_fech_inic = '';
        } else {
            $v_fech_inic = $request->input('v_fech_inic');
        }
        if ($request->input('v_fech_fina') == '') {
            $v_fech_fina = "";
        } else {
            $v_fech_fina = $request->input('v_fech_fina');
        }
        // dd($m_intIdProy,$m_intIdTipoProducto,$m_fech_fina,$m_fech_inic,$m_unidad);

        if ($v_fech_inic == "" && $v_fech_fina == "") {
            $v_flgRango = 0;
        } else {
            $v_flgRango = 1;
        }

        if ($v_intIdTipoProducto === 1) {
            DB::select('CALL sp_repo_resumencodigo(?,?,?,?,?,?,?,?,?,?,?)', array(
                $v_intIdProy,
                $v_intIdTipoProducto,
                $v_intIdProyZona,
                $v_intIdProyTarea,
                $v_intIdProyPaquete,
                $v_unidad,
                $v_flgRango,
                $v_fech_inic,
                $v_fech_fina,
                $v_opcion,
                $v_TipoGrupo
            ));
            $respuesta = DB::select('SELECT * FROM Temp_ResumenOT ORDER BY codigo');
            DB::select('DROP TABLE Temp_ResumenOT');

            return $this->successResponse($respuesta);
        }
        if ($v_intIdTipoProducto === 2) {
            DB::select('CALL sp_repo_resumencodigocompo(?,?,?,?,?,?,?,?,?,?,?)', array(
                $v_intIdProy,
                $v_intIdTipoProducto,
                $v_intIdProyZona,
                $v_intIdProyTarea,
                $v_intIdProyPaquete,
                $v_unidad,
                $v_flgRango,
                $v_fech_inic,
                $v_fech_fina,
                $v_opcion,
                $v_TipoGrupo
            ));
            $respuesta = DB::select('SELECT * FROM Temp_ResumenOT ORDER BY codigo');
            DB::select('DROP TABLE Temp_ResumenOT');

            return $this->successResponse($respuesta);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionReportes/public/index.php/gsrepo_store_valor",
     *     tags={"Reportes"},
     *     summary="Reporte de programa",
     *     @OA\Parameter(
     *         description="reporte de store valorizacion",
     *         in="path",
     *         name="v_intIdProy",
     *        example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="ingrese el id del tipo producto",
     *         in="path",
     *         name="v_intIdTipoProducto",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="ingrese el id del tipo etapa",
     *         in="path",
     *         name="v_intIdTipoEtapa",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
      @OA\Parameter(
     *         description="ingrese el id de la planta",
     *         in="path",
     *         name="v_intIdPlanta",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="ingrese el codigo de elemento",
     *         in="path",
     *         name="v_strCodigos",
     *        
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 



      @OA\Parameter(
     *         description="ingrese el id del contratista",
     *         in="path",
     *         name="v_intIdContra",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 





      @OA\Parameter(
     *         description="ingrese ingrese el valor de la semana inicio (-1 todos)",
     *         in="path",
     *         name="v_intIdSemaIni",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 

      @OA\Parameter(
     *         description="ingrese ingrese el valor de la semana final (-1 todos)",
     *         in="path",
     *         name="v_intIdSemaFin",
     *        example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
      @OA\Parameter(
     *         description="ingrese ingrese el tipo de reporte",
     *         in="path",
     *         name="v_TipoReporte",
     *        example="1",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),


     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="v_intIdproy",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="v_intIdTipoProducto",
     *                     type="string"
     *                 ) ,
      @OA\Property(
     *                     property="v_intIdTipoEtapa",
     *                     type="string"
     *                 ) ,
      @OA\Property(
     *                     property="v_intIdPlanta",
     *                     type="string"
     *                 ) ,

      @OA\Property(
     *                     property="v_intIdEtapa",
     *                     type="string"
     *                 ) ,


     *            @OA\Property(
     *                     property="v_strCodigos",
     *                     type="string"
     *                 ) ,

     *            
     *                @OA\Property(
     *                     property="v_intIdContra",
     *                     type="string"
     *                 ) ,
     * 
     *                @OA\Property(
     *                     property="v_intIdSemaIni",
     *                     type="string"
     *                 ) ,
      @OA\Property(
     *                     property="v_intIdSemaFin",
     *                     type="string"
     *                 ) ,
      @OA\Property(
     *                     property="v_TipoReporte",
     *                     type="string"
     *                 ) ,

     *                 example={"v_intIdproy": "-1","v_intIdTipoProducto":"-1","v_intIdTipoEtapa":"1","v_intIdPlanta":"-1","v_intIdEtapa":"-1","v_strCodigos":"","v_intIdContra":"-1","v_intIdSemaIni":"1","v_intIdSemaFin":"2","v_TipoReporte":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="report de store de valorizacion"
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function gsrepo_store_valor(Request $request) {

        $regla = [
            'v_intIdproy' => 'required', // -- -1 todos
            'v_intIdTipoProducto' => 'required|max:255', // -- -1 todos
            'v_intIdTipoEtapa' => 'required|max:255', /*             * -1 es todos * */
            'v_intIdPlanta' => 'required|max:255', //-- -1 TODOS
            'v_intIdEtapa' => 'required|max:255', /** -1 es todos * */
            //   'v_strCodigos'=> 'required|max:255',/**'puede ser un concatenado de códigos' **/
            'v_intIdContra' => 'required|max:255', /*             * '-1' es todos   * */
            'v_intIdSemaIni' => 'required|max:255',
            'v_intIdSemaFin' => 'required|max:255',
            'v_TipoReporte' => 'required|max:255' //-- 1 mostrar en grilla y reporte x Codigo 2:Reporte x contratista 3: Reporte x contratista y etapa;  
        ];
        $this->validate($request, $regla);

        $v_intIdproy = (int) $request->input('v_intIdproy');
        $v_intIdTipoProducto = (int) $request->input('v_intIdTipoProducto');
        $v_intIdTipoEtapa = (int) $request->input('v_intIdTipoEtapa');
        $v_intIdPlanta = (int) $request->input('v_intIdPlanta');
        $v_intIdEtapa = (int) $request->input('v_intIdEtapa');
        $v_strCodigos = $request->input('v_strCodigos');
        $v_intIdContra = $request->input('v_intIdContra');
        $v_intIdSemaIni = (int) $request->input('v_intIdSemaIni');
        $v_intIdSemaFin = (int) $request->input('v_intIdSemaFin');
        $v_TipoReporte = (int) $request->input('v_TipoReporte');

        if ($v_strCodigos == null || $v_strCodigos == '') {
            $v_strCodigos = '';
        } else {
            $v_strCodigos = trim($request->input('v_strCodigos'), ',');
        }
        if ($v_intIdContra == "-1") {
            $v_intIdContra = '-1';
        } else {
            $v_intIdContra = trim($request->input('v_intIdContra'), ',');
        }




        $respuesta = DB::select('CALL sp_valorizacion_Q01(?,?,?,?,?,?,?,?,?,?)', array(
                    $v_intIdproy,
                    $v_intIdTipoProducto,
                    $v_intIdTipoEtapa,
                    $v_intIdPlanta,
                    $v_intIdEtapa,
                    $v_strCodigos,
                    $v_intIdContra,
                    $v_intIdSemaIni,
                    $v_intIdSemaFin,
                    $v_TipoReporte
        ));


        return $this->successResponse($respuesta);
    }

    public function store_repo_libe(Request $request) {
        $regla = [
            'v_intIdproy' => 'required|max:255',
            'v_intIdTipoProducto' => 'required|max:255',
            'v_intIdZona' => 'required|max:255',
            'v_intIdTarea' => 'required|max:255',
            'v_intIdEtapaInspeccion' => 'required|max:255',
            'v_strCodigo' => 'required|max:255',
            'v_intIdInspector' => 'required|max:255',
            'v_dttFechaIni' => 'required|max:255',
            'v_dttFechaFin' => 'required|max:255',
            'v_TipoInspec' => 'required|max:255',
            'v_Contratista' => 'required|max:255',
            'v_Filtro' => 'required|max:255',
            'v_TipoReporte' => 'required|max:255'
        ];
        //dd($regla);
        $this->validate($request, $regla);

        $v_intIdproy = (int) $request->input('v_intIdproy');
        $v_intIdTipoProducto = (int) $request->input('v_intIdTipoProducto');
        $v_intIdZona = (int) $request->input('v_intIdZona');
        $v_intIdTarea = (int) $request->input('v_intIdTarea');
        $v_intIdEtapaInspeccion = (int) $request->input('v_intIdEtapaInspeccion');
        $v_strCodigo = $request->input('v_strCodigo');
        $v_intIdInspector = (int) $request->input('v_intIdInspector');
        $v_dttFechaIni = $request->input('v_dttFechaIni');
        $v_dttFechaFin = $request->input('v_dttFechaFin');
        $v_TipoInspec = (int) $request->input('v_TipoInspec');
        $v_Contratista = (int) $request->input('v_Contratista');
        $v_Filtro = (int) $request->input('v_Filtro');
        $v_TipoReporte = (int) $request->input('v_TipoReporte');

        /* dd( $v_intIdproy,
          $v_intIdTipoProducto,
          $v_intIdZona,
          $v_intIdTarea,
          $v_intIdEtapaInspeccion,
          $v_strCodigo,
          $v_intIdInspector,
          $v_dttFechaIni,
          $v_dttFechaFin,
          $v_TipoInspec,
          $v_Contratista,
          $v_TipoReporte);
         */

        $results = DB::select('CALL sp_Inspecciones_Q01(?,?,?,?,?,?,?,?,?,?,?,?,?)', array(
                    $v_intIdproy,
                    $v_intIdTipoProducto,
                    $v_intIdZona,
                    $v_intIdTarea,
                    $v_intIdEtapaInspeccion,
                    $v_strCodigo,
                    $v_intIdInspector,
                    $v_dttFechaIni,
                    $v_dttFechaFin,
                    $v_TipoInspec,
                    $v_Contratista,
                    $v_Filtro,
                    $v_TipoReporte
        ));
        //dd($results);

        return $this->successResponse($results);
    }

    public function store_repo_libe_reporte(Request $request) {
        $regla = [
            'v_intIdproy' => 'required|max:255',
            'v_intIdTipoProducto' => 'required|max:255',
            'v_intIdZona' => 'required|max:255',
            'v_intIdTarea' => 'required|max:255',
            'v_intIdEtapaInspeccion' => 'required|max:255',
            'v_strCodigo' => 'required|max:255',
            'v_intIdInspector' => 'required|max:255',
            'v_dttFechaIni' => 'required|max:255',
            'v_dttFechaFin' => 'required|max:255',
            'v_TipoInspec' => 'required|max:255',
            'v_Contratista' => 'required|max:255',
            'v_Filtro' => 'required|max:255',
            'v_TipoReporte' => 'required|max:255'
        ];
        //dd($regla);
        $this->validate($request, $regla);

        $v_intIdproy = (int) $request->input('v_intIdproy');
        $v_intIdTipoProducto = (int) $request->input('v_intIdTipoProducto');
        $v_intIdZona = (int) $request->input('v_intIdZona');
        $v_intIdTarea = (int) $request->input('v_intIdTarea');
        $v_intIdEtapaInspeccion = (int) $request->input('v_intIdEtapaInspeccion');
        $v_strCodigo = $request->input('v_strCodigo');
        $v_intIdInspector = (int) $request->input('v_intIdInspector');
        $v_dttFechaIni = $request->input('v_dttFechaIni');
        $v_dttFechaFin = $request->input('v_dttFechaFin');
        $v_TipoInspec = (int) $request->input('v_TipoInspec');
        $v_Contratista = (int) $request->input('v_Contratista');
        $v_Filtro = (int) $request->input('v_Filtro');
        $v_TipoReporte = (int) $request->input('v_TipoReporte');

        /* dd( $v_intIdproy,
          $v_intIdTipoProducto,
          $v_intIdZona,
          $v_intIdTarea,
          $v_intIdEtapaInspeccion,
          $v_strCodigo,
          $v_intIdInspector,
          $v_dttFechaIni,
          $v_dttFechaFin,
          $v_TipoInspec,
          $v_Contratista,
          $v_TipoReporte);
         */

        $results = DB::select('CALL sp_Inspecciones_Q02(?,?,?,?,?,?,?,?,?,?,?,?,?,@v_valor,@v_producido,@v_indicador)', array(
                    $v_intIdproy,
                    $v_intIdTipoProducto,
                    $v_intIdZona,
                    $v_intIdTarea,
                    $v_intIdEtapaInspeccion,
                    $v_strCodigo,
                    $v_intIdInspector,
                    $v_dttFechaIni,
                    $v_dttFechaFin,
                    $v_TipoInspec,
                    $v_Contratista,
                    $v_Filtro,
                    $v_TipoReporte
        ));
        $results = DB::select('select @v_valor,@v_producido,@v_indicador');
        return $this->successResponse($results);
    }

    public function segui_pintura(Request $request) {
        $regla = [
            'intIdProy.required' => 'EL Campo Proyecto es obligatorio',
            'intIdTipoProducto.required' => 'EL Campo Tipo Producto es obligatorio',
            'intIdCabina.required' => 'EL Campo Cabina obligatorio',
            'intIdCont.required' => 'EL Campo Contratista obligatorio',
            'intIdLotePintura.required' => 'EL Campo Lote Pintura es obligatorio',
            'fechainicio.required' => 'EL Campo Fecha Inicio es obligatorio',
            'fechafin.required' => 'EL Campo Fecha Fin es obligatorio',
            'color_busqueda.required' => 'EL Campo Tipo Producto es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdProy' => 'required|max:255',
                    'intIdTipoProducto' => 'required|max:255',
                    'intIdCabina' => 'required|max:255',
                    'intIdLotePintura' => 'required|max:255',
                    'fechainicio' => 'required|max:255',
                    'intIdCont' => 'required|max:255',
                    'fechafin' => 'required|max:255',
                    'color_busqueda' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $validar = array('data' => array());
            date_default_timezone_set('America/Lima'); // CDT
            $current_date = date('Y-m-d');
            $listar_pintura = DB::select("select concat('LP',LPAD(tab_pint.intIdLotePintura,6,'0')) as Codigo,tab_pint.*,proyecto.varCodiProy,contratista.varRazCont from tab_pint 
                                        inner join proyecto on tab_pint.intIdProy=proyecto.intIdProy
                                        inner join contratista on tab_pint.intIdCont=contratista.intIdCont
                                        where (tab_pint.intIdProy=$request->intIdProy or -1=$request->intIdProy) and tab_pint.intIdTipoProducto=$request->intIdTipoProducto 
                                        and (tab_pint.intIdCabina=$request->intIdCabina or -1=$request->intIdCabina) and 
                                        (tab_pint.intIdCont=$request->intIdCont or -1= $request->intIdCont) and
                                        (tab_pint.intIdLotePintura=$request->intIdLotePintura or -1=$request->intIdLotePintura) 
                                        and tab_pint.dateFechInic between '$request->fechainicio' and '$request->fechafin' and tab_pint.intIdEsta in(39,40)");
            for ($i = 0; count($listar_pintura) > $i; $i++) {
                /* VARIABLES GLOBALES */
                $progress_color = "";
                $estado = "";
                $progress = "";
                $rest = "";
                $color = "";
                /* DATOS DEL LOTE DE PINTURA */
                $color_tipo = $request->color_busqueda;
                $ot = $listar_pintura[$i]->varCodiProy;
                $lote_pintura = $listar_pintura[$i]->Codigo;
                $contratista = $listar_pintura[$i]->varRazCont;
                $fech_inic = $listar_pintura[$i]->dateFechInic;
                $fech_term = $listar_pintura[$i]->dateFechFin;
                $fech_real = $listar_pintura[$i]->dateFechFinReal;
                $esta_lote = $listar_pintura[$i]->intIdEsta;
                $id_lote = $listar_pintura[$i]->intIdLotePintura;
                $fecha_termino_gant = date("Y-m-d", strtotime($fech_term . "+ 1 days"));
                $fecha_termino_csv = date("Y-m-d", strtotime($fech_term));
                if ($esta_lote == "40") {
                    $color = "#60ad5e"; //green
                    $progress_color = "#1b5e20"; //green achurado
                    $estado = "TERMINADO";
                }
                
                /* SI EL ESTADO ES 39 TIENE DOS OPCIONES SI LA FECHA DE INICIO ES MAYOR A LA FECHA DEL DIA SE COLOCA AZUL YA QUE NO EMPIEZA Y NO TIENE AVANCE
                  SI LA FECHA INICIO ES MENOR A LA FECHA ACTUAL SE PINTA AMARILLO YA QUE PASO LA FECHA DE INICIO CON LA ACTUAL Y NO TIENE AVANCE */
                if ($esta_lote == "39") {

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
                if ($color_tipo === "1") {
                    $validar['data'][] = array(
                        'id' => $id_lote,
                        'ot' => $ot,
                        'contratistas' => mb_strtoupper(trim($contratista), 'UTF-8'),
                        'start_date' => $fech_inic,
                        'debi_fina' => $fech_term,
                        'end_date' => $fecha_termino_gant,
                        'Fecha_final' => $fecha_termino_csv,
                        'color' => $color,
                        'nomb_paqu' => mb_strtoupper(trim($lote_pintura), 'UTF-8'),
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
                        'id' => $id_lote,
                        'ot' => $ot,
                        'contratistas' => mb_strtoupper(trim($contratista), 'UTF-8'),
                        'start_date' => $fech_inic,
                        'debi_fina' => $fech_term,
                        'end_date' => $fecha_termino_gant,
                        'Fecha_final' => $fecha_termino_csv,
                        'color' => $color,
                        'nomb_paqu' => mb_strtoupper(trim($lote_pintura), 'UTF-8'),
                        //'text' => mb_strtoupper(trim($nomb_arma), 'UTF-8') . ' ' . mb_strtoupper(trim($apel_arma), 'UTF-8'),
                        'text' => '',
                        'diferencia_total' => $dife_dia,
                        'diferencia_espe' => $dife_espe,
                        'progress' => $progress,
                        'progressColor' => $progress_color,
                        'Estaod' => $estado
                    );
                }
            }
            return $this->successResponse($validar);
        }
    }

}
