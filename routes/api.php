<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


use App\Http\Controllers\Api\GruposController;
use App\Http\Controllers\Api\PartidosController;
use App\Http\Controllers\Api\EquiposController;
use App\Http\Controllers\Api\PlayersController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\UserController;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\TorneoController;
use App\Http\Controllers\Api\EliminatoriaController;


use App\Http\Controllers\Api\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });





Route::controller(GruposController::class)->group(function () {
    Route::get('/grupos', 'index')->name('grupos.index');
    Route::get('/grupos/{subcategoriaId}', 'gruposXsubcategoria')->name('grupos.gruposXsubcategoria');
    Route::get('/gruposp', 'admin')->name('grupos.admin');
    Route::post('/grupo', 'store')->name('grupos.store');
    Route::delete('/grupo/{id}', 'destroy')->name('grupo.destroy');
    Route::put('/grupo/{id}', 'update')->name('grupo.update');
});




Route::controller(EquiposController::class)->group(function () {
    Route::get('/equipos', 'index')->name('equipos.index');
    Route::get('/equipos/{grupoId}', 'equiposXgrupo')->name('equipos.equiposXgrupo');
    Route::post('/equipo', 'store')->name('equipo.store');
    Route::delete('/equipo/{id}', 'destroy')->name('equipo.destroy');
    Route::put('/equipo/{id}', 'update')->name('equipo.update');
});

Route::controller(PartidosController::class)->group(function () {
    Route::get('/partidos', 'index')->name('partidos.index');
    Route::post('/partido', 'store')->name('partido.store');
    Route::delete('/partido/{id}', 'destroy')->name('partido.destroy');
    Route::put('/partido/{id}', 'update')->name('partido.update');
});

Route::controller(PlayersController::class)->group(function () {
    Route::get('/jugadores', 'index')->name('jugadores.index');
    Route::post('/jugador', 'store')->name('jugador.store');
    Route::delete('/jugador/{id}', 'destroy')->name('jugadores.destroy');
    Route::put('/jugador/{id}', 'update')->name('jugador.update');
    Route::get('/jugadores/{id}', 'show')->name('jugador.show');
 



});
// En routes/api.php (Laravel)
Route::get('equipos/{equipoId}/jugadores', [PlayersController::class, 'getJugadoresPorEquipo']);


Route::controller(EliminatoriaController::class)->group(function () {
    Route::get('/eliminatorias', 'index')->name('eliminatorias.index');
    Route::get('/eliminatoria/subcategoria/{id}', 'getEliminatoriasBySubcategoria')->name('eliminatorias.getEliminatoriasBySubcategoria');
    Route::post('/eliminatoria', 'store')->name('eliminatorias.store');
    Route::delete('/eliminatorias/{id}', 'destroy')->name('eliminatorias.destroy');
    Route::put('/eliminatoria/{id}', 'update')->name('eliminatoria.update');
});



Route::controller(TableController::class)->group(function () {
    Route::get('/clasificacion', 'index')->name('clasificacion.index');
   
});
Route::controller(UserController::class)->group(function () {
    Route::get('/userTeams', 'Teams')->name('equipos.Teams');

    Route::get('/userHomeTeams', 'HomeTeams')->name('equipos.HomeTeams');
    Route::get('/userHomeclassification', 'Homeclassification')->name('grupos.Homeclassification');
   
});
Route::controller(CategoryController::class)->group(function () {
    Route::get('/torneo/{id}/categorias', 'index')->name('categoria.index');
    Route::get('/categorias/{torneoId}', 'categoriaxtorneo')->name('categoria.categoriaxtorneo');
    Route::get('/categorias', 'admin')->name('categoria.admin');
    Route::post('/categoria', 'store')->name('categoria.store');
    Route::delete('/categoria/{id}', 'destroy')->name('categoria.destroy');
    Route::put('/categoria/{id}', 'update')->name('categoria.update');
    Route::get('/categoriasp', 'paginador')->name('categoria.paginador');
});




Route::controller(SubCategoryController::class)->group(function () {
    Route::get('/categoria/{id}/subcategorias', 'index')->name('subcategoria.index');
    Route::post('/subcategoria', 'store')->name('subcategoria.store');
    Route::get('/subcategorias', 'admin')->name('subcategoria.admin');
    Route::delete('/subcategoria/{id}', 'destroy')->name('subcategoria.destroy');
    Route::put('/subcategoria/{id}', 'update')->name('subcategoria.update');
    Route::get('/subcategoriasp', 'paginador')->name('subcategoria.paginador');
    // Route::delete('/subcategoria/{id}', 'destroy')->name('subcategoria.destroy');
   
});
Route::get('/subcategorias/{subcategoriaId}', [SubCategoryController::class, 'show']);
Route::get('/partidos/subcategoria/{subcategoriaId}', [SubCategoryController::class, 'indexPorSubcategoria']);

Route::controller(TorneoController::class)->group(function () {
    Route::get('/torneos', 'index')->name('torneo.index');
    Route::get('/torneosp', 'paginador')->name('torneo.paginador');
    Route::post('/torneo', 'store')->name('torneo.store');
    Route::delete('/torneo/{id}', 'destroy')->name('torneo.destroy');
    Route::put('/torneo/{id}', 'update')->name('torneo.update');
});


Route::get('/subcategoria/{subcategoriaId}/equipos', [SubCategoryController::class, 'equiposPorSubcategoria']);
Route::get('/subcategoria/{subcategoriaId}/partidos', [SubCategoryController::class, 'partidosPorSubcategoria']);
Route::get('/subcategoria/{subcategoriaId}/clasificacion', [SubCategoryController::class, 'ClasificacionPorSubcategoria']);
Route::get('/subcategoria/{subcategoriaId}/Inicioclasificacion', [SubCategoryController::class, 'ClasificacionInicioPorSubcategoria']);

Route::get('/subcategoria/{subcategoriaId}/jugadores', [SubCategoryController::class, 'jugadoresPorSubcategoria']);
Route::get('/subcategoria/{subcategoriaId}/jugadores/paginador', [SubCategoryController::class, 'jugadoresPorSubcategoriaPaginador']);

Route::get('/subcategoria/{subcategoriaId}/partidos/paginador', [SubCategoryController::class, 'partidosPorSubcategoriaPaginador']);




// Route::get('/subcategorias/{subcategoriaId}/equipos', [SubCategoryController::class, 'equiposPorSubcategoria']);



// Route::resource('equipos', EquiposController::class);
// Route::resource('grupos', GruposController::class);



Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(csrf_token());
});

// Route::middleware('web')->get('/sanctum/csrf-cookie', function () {
//     return response()->json(csrf_token());
// });