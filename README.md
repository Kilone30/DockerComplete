# DockerComplete
Proyecto Integral: Sistema de Gestión con Docker + Laravel + Flutter + PostgreSQL

Este proyecto es un sistema completo desarrollado utilizando:

Flutter Web como frontend (interfaz de usuario)

Laravel como backend (API REST)

PostgreSQL como base de datos

Docker Compose para contenedorización

Arquitectura conectada mediante microservicios internos
🐳 Contenedores utilizados
Servicio	Puerto Local	Contenedor
Frontend	8080	supervisores_frontend
Backend API	9000	supervisores_backend
PostgreSQL	5433	supervisores_db

Cómo ejecutar el proyecto
1️⃣ Clonar el repositorio
git clone https://github.com/kilone30/Dockercomplete.git

cd Dockercomplete

docker compose up -d --build
Luego ejecutar en el navegador:
http://localhost:8080

Autores 
Fernando Rafael Medina Pezaña
Itzel Berenice Alvarado Olivares
