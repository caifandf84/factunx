<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/terminos_condiciones', function () {
    return view('terminos_condiciones');
});
Auth::routes();

Route::get('/home', 'HomeController@index');



Route::get('/documentos', 'DocumentController@index');

Route::get('/documentos/tipo/{new}/{id}', 'DocumentController@type');

Route::get('/documentos/ver/lista/emitidos', 'DocumentController@verListaEmitidos');

Route::get('/documentos/ver/cancelar', 'DocumentController@verParaCancelar');

Route::any('/documentos/emision/datosGeneral','DocumentEmisionController@emisionDatoGeneral');

Route::any('/documentos/emision/conceptosTotales', 'DocumentEmisionController@conceptosTotales');

Route::post('/documentos/emision/generar', 'DocumentEmisionController@enviarEmision');

Route::get('/documentos/emision/impuestoPorTipo', 'DocumentEmisionController@getImpuestoJson');

Route::get('/domicilio/pais/', 'DomicileController@getListPaisJson');

Route::get('/domicilio/comboMunicipoPorIdEdo','DomicileController@getComboMunicipioJson');

Route::get('/domicilio/comboColoniaPorIdMunicipio','DomicileController@getComboColoniaJson');

Route::get('/domicilio/codigoPostalPorIdColonia','DomicileController@getCodigoPostalJson');

Route::get('/domicilio/direccionPorCodigoPostal','DomicileController@getDireccionPorCPJson');

Route::get('/documentos/listaGrid','DocumentEmisionListController@getListaEmitidosJson');

Route::get('/documentos/enviaCorreo','DocumentEmisionListController@getSendEmail');

Route::get('/documentos/emision/exportarDocumento','DocumentEmisionListController@getDocumentoExport');

Route::get('/documentos/emision/testFactura','DocumentEmisionController@testFactura');

Route::get('/documentos/emision/testValores','DocumentEmisionController@testValores');

Route::get('/documentos/cancelacion/listado','DocumentCancelacionController@getListaEmitidosJson');

Route::get('/documentos/emision/uuid','DocumentEmisionController@getDocumentoUuidJson');

Route::get('/documentos/cancelacion/cancrelarPorId','DocumentCancelacionController@cancelarDocumento');

Route::get('/contribuyente/listaGrid','ContribuyenteController@getListaContribuyentesJson');

Route::get('/configuracion/concepto', 'ConfigurationController@indexConcepto');

Route::get('/configuracion/serie_numero', 'ConfigurationController@indexSerieNumero');

Route::get('/configuracion/imagen_empresa', 'ConfigurationController@indexCambiarImagenPdf');

Route::post('/configuracion/imagen_empresa/actualizar', 'ConfigurationController@cambiarImagenPdf');

Route::get('/concepto/autocmplete', 'ConceptoController@getAutocompleteCodigoJson');

Route::get('/concepto/obtenerPorId', 'ConceptoController@getByIdJson');

Route::get('/concepto/obtenerPorCodigoBarras', 'ConceptoController@getByCodigoBarrasJson');

Route::get('/concepto/listaGrid','ConceptoController@getListaConceptosJson');

Route::get('/productoServicio/listaGrid','ProductoServicioController@getListaProductoServicioJson');

Route::get('/configuracion/concepto/generaCodBarras', 'ConfigurationController@exportCodigoBarras');

Route::get('/configuracion/concepto/guardar', 'ConfigurationController@guardarConceptos');

Route::get('/administracion/contribuyente', 'AdministratorController@indexDatosContribuyente');

Route::post('/administracion/actualizar/contribuyente', 'AdministratorController@actualizaContribuyente');

Route::get('/administracion/obtenerRegimenFiscal', 'AdministratorController@getRegimenFiscalJson');

Route::get('/administracion/cambiarPassword', 'AdministratorController@indexAcutalizaPasswordUser');//indexUsers

Route::post('/administracion/user/password/actualizar', 'AdministratorController@postCambioPassword');

Route::get('/administracion/usuarios', 'AdministratorController@indexUsers');

Route::get('/contribuyente/listaUsuariosGrid','AdministratorController@getListaUsuariosJson');

Route::get('/reporte/timbrado', 'DashboardController@indexTimbrado');

Route::get('/reporte/timbrado/{mes}/{anio}', 'DashboardController@indexMesAnioTimbrado');

Route::get('/productos', 'ProductoController@index');

Route::get('/producto/comprar/{idProducto}', 'CompraProductoController@index');

Route::get('/producto/comprar/form/conTarjeta', 'CompraProductoController@verPagarConTarjeta');

Route::get('/producto/comprar/form/efectivo', 'CompraProductoController@verPagarReferencia');

Route::post('/producto/comprado/medioPago/referencia', 'CompraProductoController@referenciaPagada');

Route::post('/producto/comprar/pagar/medioDePago', 'CompraProductoController@procesarPago');

Route::get('/producto/comprado/listaProductosGrid', 'CompraProductoController@getListaProductosJson');

Route::get('/producto/comprado/listaProductos', 'CompraProductoController@getListaProductosComprado');

Route::get('/registrar/contribuyente/test', 'DocumentController@testAsignacion');

Route::post('/registrar/contribuyente/seleccionado', 'Auth\RegisterController@registerContribuyente');

Route::get('/registrar/contribuyente/aceptado/{id}', 'Auth\RegisterController@aceptarUsuario');

Route::get('/serieNumero/listaSerieNumeroGrid', 'SerieNumeroController@getListaSerieNumeroJson');

Route::get('/serieNumero/listaSerieNumero', 'SerieNumeroController@getListaSerieNumero');

Route::post('/configuracion/serie_numero/crud', 'ConfigurationController@crudSerieNumero');

Route::get('/timbrado/infac', 'DocumentEmisionController@getInfacFormulario');

Route::post('/validador/cfdi222', 'ValidadorController@validaCFDITimbrado');

Route::get('/validador/acceso', 'ValidadorController@createAccess');

//guardar 20 registros fallidos

Route::get('/documentos/tmp/listado','EmisionTmpController@getListaPorEmitirJson');

Route::get('/documentos/ver/tmpEmision', 'DocumentController@verListaTmpEmitidos');

Route::get('/documentos/ver/continuar/tmpEmision', 'EmisionTmpController@verLSeleccionTmpEmision');

Route::post('/documentos/verPrevio','DocumentEmisionController@verPrevio');

//exponer WS Documento

Route::post('/api/documento/emitir','DocumentoEmisionWSController@generaDocumento');

Route::get('/api/documento/buscar','DocumentoEmisionWSController@obtenerDocumento');
