<?php

namespace App\Http\Controllers;

use App\Programas;
use App\Software;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DateTime;
use Illuminate\Support\Facades\Validator;

class GalvanizadoController extends Controller {

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

    public function listar_peso_galvanizado(Request $request) {
        $regla = ['dateFechIngr.required' => 'EL Campo Fecha Inicio es obligatorio',
            'dateFechSali.required' => 'EL Campo Fecha Fin es obligatorio'];
        $validator = Validator::make($request->all(), ['dateFechIngr' => 'required|max:255',
                    'dateFechSali' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            /*PARA EL PESO DE GALVANIZADO SOLO SUMA SI EL TIPO ES GALVANIZADO Y PARA EL ZINC SUMA INDIFERENTEMENTE SUMA TANTO GALVANIZADO,FLUXADO,REGALVANIZADO*/
            $Deta_galva = DB::select("select  tab_galv.varTipoOrden tipo,sum(case when deta_galv.varTipoGalv='GALVANIZADO'   then deta_galv.deciPesoGalv end) peso ,  concat(ROUND(ifnull((sum(deta_galv.deciConsumoZinc) / sum(case when deta_galv.varTipoGalv='GALVANIZADO'   then deta_galv.deciPesoNegro end))*100,0),3) ,' %')    as  zinc  from tab_galv
                inner join deta_galv on tab_galv.intIdGalva=deta_galv.intIdGalva
                where tab_galv.dateFechIngr between '$request->dateFechIngr' and '$request->dateFechSali'  and deta_galv.intIdEsta<>35
                group by 1 ");
            return $this->successResponse($Deta_galva);
        }
    }

    public function listar_peso_negro(Request $request) {
        $regla = ['dateFechIngr.required' => 'EL Campo Fecha Inicio es obligatorio',
            'dateFechSali.required' => 'EL Campo Fecha Fin es obligatorio'];
        $validator = Validator::make($request->all(), ['dateFechIngr' => 'required|max:255',
                    'dateFechSali' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            /*el tipo de material se suma solo cuando sean galvanizado*/
            $Deta_galva = DB::select("select  varTipoOrden,sum(case when deta_galv.varTipoMate='LIVIANO' then  deta_galv.deciPesoNegro else 0.0 end)  as  PesoLiviano,
                sum(case when deta_galv.varTipoMate='PESADO' then  deta_galv.deciPesoNegro else 0.0 end)  as  PesoPesado,
                sum(case when deta_galv.varTipoMate='SEMIPESADO' then  deta_galv.deciPesoNegro else 0.0 end)  as  PesoSemiPesado
                from deta_galv
                left join tab_galv on tab_galv.intIdGalva = deta_galv.intIdGalva   where tab_galv.intIdGalva = deta_galv.intIdGalva  
                and dateFechIngr between '$request->dateFechIngr' and '$request->dateFechSali' and deta_galv.varTipoGalv='GALVANIZADO' and deta_galv.intIdEsta<>35
                group by tab_galv.varTipoOrden");
            //dd($Deta_galva);
            for ($i = 0; count($Deta_galva) > $i; $i++) {
                
            }
            return $this->successResponse($Deta_galva);
        }
    }

    public function repo_consumo_zinc(Request $request) {
        $regla = ['dateFechIngr.required' => 'EL Campo Fecha Inicio es obligatorio',
            'dateFechSali.required' => 'EL Campo Fecha Fin es obligatorio'];
        $validator = Validator::make($request->all(), ['dateFechIngr' => 'required|max:255',
                    'dateFechSali' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            /*EL COMSUMO DE ZCIN ESTA SUMANDO INDIFERENTEMENTE YA SEA GALVANZIADO,FLUXADO,REGALVANIZADO*/
            $Deta_galva = DB::select("select  varTipoOrden,concat(ROUND(ifnull((sum(case when deta_galv.varTipoMate='LIVIANO' then deta_galv.deciConsumoZinc else 0.0 end) / sum(case when deta_galv.varTipoMate='LIVIANO' and deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoNegro else 0.0 end))*100,0),3) ,' %')    as  PesoLiviano,
concat(ROUND(ifnull((sum(case when deta_galv.varTipoMate='PESADO' then deta_galv.deciConsumoZinc else 0.0 end ) / sum(case when deta_galv.varTipoMate='PESADO' and deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoNegro else 0.0 end))*100,0),3) ,' %')   as  PesoPesado,
concat(ROUND(ifnull((sum(case when deta_galv.varTipoMate='SEMIPESADO' then deta_galv.deciConsumoZinc else 0.0 end) / sum(case when deta_galv.varTipoMate='SEMIPESADO' and deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoNegro else 0.0 end))*100,0),3) ,' %')   as  PesoSemiPesado
            from deta_galv
            left join tab_galv on tab_galv.intIdGalva = deta_galv.intIdGalva   where tab_galv.intIdGalva = deta_galv.intIdGalva  and deta_galv.intIdEsta<>35
            and tab_galv.dateFechIngr between '$request->dateFechIngr' and '$request->dateFechSali'
            group by tab_galv.varTipoOrden");
            
            
            
            return $this->successResponse($Deta_galva);
        }
    }

    public function reporte_galvanizado_turno(Request $request) {
        $regla = ['dateFechIngr.required' => 'EL Campo Fecha Inicio es obligatorio',
            'dateFechSali.required' => 'EL Campo Fecha Fin es obligatorio',
            'tipo_reporte.required' => 'EL Campo Fecha Fin es obligatorio'];
        $validator = Validator::make($request->all(), ['dateFechIngr' => 'required|max:255',
                    'dateFechSali' => 'required|max:255',
                    'tipo_reporte' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $tipo_reporte = $request->tipo_reporte;
            $fecha_inicio = (int) $request->dateFechIngr;
            $fecha_fin = (int) $request->dateFechSali;
            DB::select("CALL sp_Galvanizado_R01(?,?,?)", array(
                $tipo_reporte,
                $fecha_inicio,
                $fecha_fin
            ));
            $resultado = DB::select("SELECT ntipo,strSemana,Fecha,UnidadMimco,UnidadTercero,TotalUnidad,T1mimco,T1tercero,TotalT1,T2mimco,T2tercero,TotalT2 FROM galvanizado");
            DB::select("DROP TABLE galvanizado");
            return $this->successResponse($resultado);
        }
    }

    public function reporte_inspeccion_glavanizado(Request $request) {
        $regla = ['dateFechIngr.required' => 'EL Campo Fecha Inicio es obligatorio',
            'dateFechSali.required' => 'EL Campo Fecha Fin es obligatorio',
            'tipoReporte.required' => 'EL Campo Fecha Fin es obligatorio'];
        $validator = Validator::make($request->all(), ['dateFechIngr' => 'required|max:255',
                    'dateFechSali' => 'required|max:255',
                    'tipoReporte' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {

            if ($request->tipoReporte === '1') {
                DB::select('CALL sp_ReporteInspGalvanizado(?,?)', array(
                    $request->dateFechIngr,
                    $request->dateFechSali,
                ));
                $select_data = DB::select("select * from temp_inspecciones");
                DB::select("drop table temp_inspecciones ");
                return $this->successResponse($select_data);
            } else {
                DB::select('CALL sp_ReporteRecExceo(?,?)', array(
                    $request->dateFechIngr,
                    $request->dateFechSali,
                ));
                $select_data = DB::select("select * from temp_inspecciones");
                DB::select("drop table temp_inspecciones ");
                return $this->successResponse($select_data);
            }
        }
    }

}
