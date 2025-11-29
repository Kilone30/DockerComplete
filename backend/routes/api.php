<?php

use App\Http\Controllers\EquivalenciaController;
use App\Http\Controllers\LicenciaturaController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\TutorController;
use App\Http\Middleware\Cerbero;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController;

Route::prefix('v1')->group(function () {
    
    Route::get('/alumnos', [AlumnoController::class, 'getAlumnos']);
    Route::get('/alumnos/cuenta/{num_cuenta}', [AlumnoController::class, 'getAlumno']);
    Route::get('/alumnos/licenciatura/{licenciatura}', [AlumnoController::class, 'getAlumnosLicenciatura']);
    Route::get('/alumnos/genero/{genero}', [AlumnoController::class, 'getAlumosGenero']);
    Route::get('/alumnos/total', [AlumnoController::class, 'getTotalAlumnos']);
    Route::get('/alumnos/sin-tutor', [AlumnoController::class, 'getAlumnosSinTutor']);
    Route::post('/alumnos', [AlumnoController::class, 'postAlumno']);
    Route::put('/alumnos/cuenta/{num_cuenta}', [AlumnoController::class, 'putAlumno']);

    Route::get('/materias', [MateriaController::class, 'getAllMaterias']);
    Route::get('/materias/{clave}', [MateriaController::class, 'getMateria']);

    Route::get('/equivalencias/{clave}', [EquivalenciaController::class, 'getEquivalencias']);

    Route::get('/licenciaturas', [LicenciaturaController::class, 'getLicenciaturas']);

    Route::get('/periodos', [PeriodoController::class, 'getPeriodos']);
    Route::get('/periodos/actual', [PeriodoController::class, 'getPeriodoActual']);
    Route::get('/periodos/periodo/{periodo}', [PeriodoController::class, 'getPeriodo']);
    Route::get('/periodos/periodo/{periodo}/exists', [PeriodoController::class, 'getPeriodoCheckExists']);
    Route::post('/periodos', [PeriodoController::class, 'postPeriodo']);
    Route::put('/periodos/periodo/{periodo}', [PeriodoController::class, 'putPeriodo']);

    Route::get('/docentes', [DocenteController::class, 'getDocentes']);
    Route::post('/docentes', [DocenteController::class, 'postDocente']);
    Route::get('/docentes/rfc/{rfc}', [DocenteController::class, 'getDocente']);
    Route::get('/docentes/rfc/{rfc}/exists', [DocenteController::class, 'getDocenteCheckExists']);
    Route::put('/docentes/rfc/{rfc}', [DocenteController::class, 'putDocente']);

    Route::get('/tutores', [TutorController::class, 'getTutores']);
    Route::get('/tutores/tutores-individuales', [TutorController::class, 'getTutoresIndividuales']);
    Route::get('/tutores/tutores-equipo', [TutorController::class, 'getTutoresEquipos']);
    Route::get('/tutores/rfc/{rfc}', [TutorController::class, 'getTutor']);
    Route::delete('/tutores/rfc/{rfc}', [TutorController::class, 'deleteTutor']);
    Route::get('/tutores/rfc/{rfc}/tutorados', [TutorController::class, 'getTutorados']);
    Route::get('/tutores/licenciatura/{licenciatura}', [TutorController::class, 'getTutoresLicenciatura']);
    Route::get('/tutores/rfc/{rfc}/total-tutorados', [TutorController::class, 'getNumeroTutorados']);
    Route::put('/tutores/rfc/{rfc}/tutor-equipo', [TutorController::class, 'putTutorEquipo']);
    Route::put('/tutores/rfc/{rfc}/asignar-tutorado', [TutorController::class, 'putTutorado']);
    Route::put('/tutores/rfc/{rfc}/cambio-tutor', [TutorController::class, 'putTutorCambio']);
});
