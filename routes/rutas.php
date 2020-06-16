<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 
$router->post('/segu_grup','GruposController@segu_grup');

/*

$router->post('/validar_usuario','UsuarioController@validar_usuario');
$router ->post('/actu_usua','UsuarioController@actu_usua');
$router->post('/elim_usua','UsuarioController@elim_usua');
$router->get('/list_usua','UsuarioController@list_usua');


*/

$router->post('/list_ot_o_todas_ot','ReportesController@list_ot_o_todas_ot');
$router->post('/repo_zona','ReportesController@repo_zona');
$router->post('/repo_prog','ReportesController@repo_prog');
$router->post('/repo_codi','ReportesController@repo_codi');
$router->post('/repo_grup','ReportesController@repo_grup');
$router->post('/list_ot_o_todas_ot_vers2','ReportesController@list_ot_o_todas_ot_vers2');
$router->post('/repo_zona_vers2','ReportesController@repo_zona_vers2');
$router->post('/segui_pintura','ReportesController@segui_pintura');

$router->post('/gsrepo_store_valor','ReportesController@gsrepo_store_valor');

$router->post('/reporte_galvanizado_turno','GalvanizadoController@reporte_galvanizado_turno');
//REPORTAR LIBERACION 

$router->post('/store_repo_libe','ReportesController@store_repo_libe');
$router->post('/listar_peso_galvanizado','GalvanizadoController@listar_peso_galvanizado');
$router->post('/listar_peso_negro','GalvanizadoController@listar_peso_negro');
$router->post('/repo_consumo_zinc','GalvanizadoController@repo_consumo_zinc'); 
$router->post('/store_repo_libe_reporte','ReportesController@store_repo_libe_reporte');
$router->post('/reporte_inspeccion_glavanizado','GalvanizadoController@reporte_inspeccion_glavanizado');

