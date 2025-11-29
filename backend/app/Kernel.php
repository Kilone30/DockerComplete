<?php

namespace App;

/**
 * @OA\Info(
 *   version="1.0.0",
 *   title="Documentación Kernel",
 *   description="Esta es la documentación del Kernel API, la cual incluye las rutas, parametros, respuestas, descripciones o recursos interactivos de cada uno de los servicios ofrecidos. Cada uno de estos servicios proporciona diferentes rutas que satisfacen una necesidad limitada y específica relacionada con el contexto del servicio. Los servicios que incluye son los siguientes:
 *     Servicio de Alumnos
 *     Servicio de Docentes
 *     Servicio de Tutores
 *     Servicio de Materias
 *     Servicio de Periodos
 *     Servicio de Licenciaturas
 *     Servicio de Equivalencias"
 * )
 * 
 * @OA\Server(
 *   url=L5_SWAGGER_CONST_HOST
 * )
 * 
 * @OA\SecurityScheme(
 *  securityScheme="bearerAuth",
 *  in="header",
 *  name="bearerAuth",
 *  type="http",
 *  scheme="bearer",
 *  bearerFormat="JWT"
 * )
 * 
 * @OA\Tag(
 *   name="Alumnos",
 *   description="Rutas que ofrece el servicio"
 * )
 *
 * @OA\Tag(
 *   name="Docentes",
 *   description="Rutas que ofrece el servicio"
 * )
 *
 * @OA\Tag(
 *   name="Tutores",
 *   description="Rutas que ofrece el servicio"
 * )
 *
 * @OA\Tag(
 *   name="Materias",
 *   description="Rutas que ofrece el servicio"
 * )
 *
 * @OA\Tag(
 *   name="Periodos",
 *   description="Rutas que ofrece el servicio"
 * )
 * 
 * @OA\Tag(
 *   name="Licenciaturas",
 *   description="Rutas que ofrece el servicio"
 * )
 * 
 * @OA\Tag(
 *   name="Equivalencias",
 *   description="Rutas que ofrece el servicio"
 * )
 */
class Kernel {}
