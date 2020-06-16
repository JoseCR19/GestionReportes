<?php

namespace App\Http\Controllers;

use App\Programas;
use App\Software;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DateTime;
use Illuminate\Support\Facades\Validator;

class PesosController extends Controller {

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

    

   
    public function llistar_pesos_ot(Request $request){
        $regla = ['v_varintIdProy.required' => 'EL Campo Fecha Inicio es obligatorio',
            'v_tipo.required' => 'EL Campo Fecha Fin es obligatorio',
            'v_usuario.required' => 'EL Campo Fecha Fin es obligatorio'];
        $validator = Validator::make($request->all(), ['v_varintIdProy' => 'required|max:255',
                    'v_tipo' => 'required|max:255','v_usuario' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            /*dd($request->input('v_varintIdProy'),
               (int) $request->v_tipo,
                $request->v_usuario);*/
            $v_strIdPaquete = trim($request->input('v_varintIdProy'), ',');
            $v_strusuario = $request->input('v_usuario');
            $tipo= (int) $request->v_tipo;
           
            DB::select('CALL sp_ReportePesos(?,?,?)', array(
                $v_strIdPaquete,
                $tipo,
                $v_strusuario
            ));
           
            $listar_pesos = DB::select("select * from tab_pesos where varUsuario='$request->v_usuario' and intIdProy in($v_strIdPaquete)");
             dd($listar_pesos);
            for($i=0;count($listar_pesos)>$i;$i++){
                
            }
            
            $elements = explode(" /", $_POST['ot']);
            return $this->successResponse($listar_ot);
        }
    }
    public function listqar_pesos_sub_ot(Request $request){
        
        $regla = ['v_intIdProy.required' => 'EL Campo Fecha Inicio es obligatorio',
            'v_tipo.required' => 'EL Campo Fecha Fin es obligatorio',
            'v_usuario.required' => 'EL Campo Fecha Fin es obligatorio'];
        $validator = Validator::make($request->all(), ['v_intIdProy' => 'required|max:255',
                    'v_tipo' => 'required|max:255','v_usuario' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $listar_sub_ot=DB::select("CALL sp_ReportePesos (?,?,?)", array(
                $request->v_intIdProy,
                $request->v_tipo,
                $request->v_usuario,
            ));
            return $this->successResponse($listar_sub_ot);
        }
    }
    public function listar_ot_pesos(Request $request){
        $regla = ['intIdUniNego.required' => 'EL Campo Fecha Inicio es obligatorio',
            'intIdEsta.required' => 'EL Campo Fecha Fin es obligatorio'];
        $validator = Validator::make($request->all(), ['intIdUniNego' => 'required|max:255',
                    'intIdEsta' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $listar_ot = DB::select("select  * from proyecto where intIdUniNego='$request->intIdUniNego' and intIdEsta=$request->intIdEsta");
            return $this->successResponse($listar_ot);
        }
    }

}
