import 'package:atlas/inicio.dart';
import 'package:atlas/main.dart';
import 'package:atlas/tutor/actualizar.dart';
import 'package:atlas/tutor/alta.dart';
import 'package:atlas/tutor/consultar.dart';
import 'package:atlas/tutor/colaborativos.dart';
import 'package:atlas/tutorados/nuevo_ingreso.dart';
import 'package:atlas/tutorados/poblacion.dart';
import 'package:flutter/material.dart';
import 'package:atlas/session.dart';



class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Descargas',
      theme: ThemeData(
        useMaterial3: true,
        colorScheme: ColorScheme.fromSeed(
            seedColor: const Color.fromARGB(255, 34, 255, 244)),
      ),
      home: DescargasPage(),
    );
  }
}

class DescargasPage extends StatefulWidget {
  @override
  // ignore: library_private_types_in_public_api
  _DescargasPageState createState() => _DescargasPageState();
}

class _DescargasPageState extends State<DescargasPage> {
  Color _inicioColor = Colors.white;
  Color _salirColor = Colors.white;
  String _mensajeExito = '';

  void _subirArchivo() async {
  try {
    // Aquí puedes implementar la lógica para subir el archivo a un servidor o base de datos
    // Por ahora, solo vamos a simular que se sube correctamente
    setState(() {
      _mensajeExito = 'Archivo descargado exitosamente.';
    });
  } catch (e) {
    print('Error al descargar el archivo: $e');
    setState(() {
      _mensajeExito = 'Error al descargar el archivo.';
    });
  }
}

  

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        automaticallyImplyLeading: false, 
        title: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Row(
              children: [
                Text(
                  '    A T L A S    | ',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 25,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                MouseRegion(
                  onEnter: (_) => setState(() => _inicioColor = const Color.fromARGB(255, 241, 218, 5)),
                  onExit: (_) => setState(() => _inicioColor = Colors.white),
                  child: TextButton(
                    onPressed: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (context) => InicioPage(token: Session.token,)),
                      );
                    },
                    child: Text(
                      'Inicio',
                      style: TextStyle(
                        color: _inicioColor,
                        fontSize: 15,
                        fontWeight: FontWeight.normal,
                      ),
                    ),
                  ),
                ),
                
                VerticalDivider(color: Colors.white),
                // Popup para "Tutores"
                MouseRegion(
                  onEnter: (_) => setState(() => _inicioColor = const Color.fromARGB(255, 241, 218, 5)),
                  onExit: (_) => setState(() => _inicioColor = Colors.white),
                  child: PopupMenuButton<String>(
                    onSelected: (String result) {
                      if (result == 'actualizas') {
                        Navigator.push(
                          context,
                          MaterialPageRoute(builder: (context) => ActualizarPage()),
                        );
                      } else {
                        // Aquí puedes agregar la navegación para otras opciones
                        print(result);
                      }
                      if (result == 'dar_de_alta') {
                        Navigator.push(
                          context,
                          MaterialPageRoute(builder: (context) => AltaPage()),
                        );
                      } else {
                        // Aquí puedes agregar la navegación para otras opciones
                        print(result);
                      }
                      if (result == 'eliminar') {
                        Navigator.push(
                          context,
                          MaterialPageRoute(builder: (context) => ColaborativoPage()),
                        );
                      } else {
                        // Aquí puedes agregar la navegación para otras opciones
                        print(result);
                      }
                      if (result == 'consultas') {
                        Navigator.push(
                          context,
                          MaterialPageRoute(builder: (context) => ConsultaPage()),
                        );
                      } else {
                        // Aquí puedes agregar la navegación para otras opciones
                        print(result);
                      }
                    },
                    
                    itemBuilder: (BuildContext context) => <PopupMenuEntry<String>>[
                      PopupMenuItem<String>(
                        value: 'dar_de_alta',
                        child: Text('Dar de Alta'),
                      ),
                      PopupMenuItem<String>(
                        value: 'eliminar',
                        child: Text('Activar Colaborativos'), //eliminar
                      ),
                      PopupMenuItem<String>(
                        value: 'eliminar',
                        child: Text('Activar Individuales'), //eliminar
                      ),
                      PopupMenuItem<String>(
                        value: 'actualizas',
                        child: Text('Actualizar Datos'),
                      ),
                      PopupMenuItem<String>(
                        value: 'consultas',
                        child: Text('Consultar')
                       ),
                    ],
                    child: Text(
                      'Tutores',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 15,
                        fontWeight: FontWeight.normal,
                      ),
                    ),
                  ),
                ),

                VerticalDivider(color: Colors.white),

                // Popup para "Tutorados"
                PopupMenuButton<String>(
                  onSelected: (String result) {
                    if (result == 'poblacion') {
                        Navigator.push(
                          context,
                          MaterialPageRoute(builder: (context) => PoblacionPage()),
                        );
                      } else {
                        // Aquí puedes agregar la navegación para otras opciones
                        print(result);
                      }
                      if (result == 'nuevo_ingreso') {
                        Navigator.push(
                          context,
                          MaterialPageRoute(builder: (context) => NuevoIngresoPage()),
                        );
                      } else {
                        // Aquí puedes agregar la navegación para otras opciones
                        print(result);
                      }
                  },
                  itemBuilder: (BuildContext context) => <PopupMenuEntry<String>>[
                    PopupMenuItem<String>(
                      value: 'poblacion',
                      child: Text('Población'),
                    ),
                    PopupMenuItem<String>(
                      value: 'nuevo_ingreso',
                      child: Text('Nuevo Ingreso'),
                    ),
                  ],
                  child: Text(
                    'Tutorados',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 15,
                      fontWeight: FontWeight.normal,
                    ),
                  ),
                ),

                VerticalDivider(color: Colors.white),

                // Popup para "Respaldo"
                PopupMenuButton<String>(
                  onSelected: (String result) {
                    if (result == 'descargar') {
                        Navigator.push(
                          context,
                          MaterialPageRoute(builder: (context) => DescargasPage()),
                        );
                      } else {
                        // Aquí puedes agregar la navegación para otras opciones
                        print(result);
                      }
                    print(result);
                  },
                  itemBuilder: (BuildContext context) => <PopupMenuEntry<String>>[
                    PopupMenuItem<String>(
                      value: 'descargar',
                      child: Text('Descargar'),
                    ),
                  ],
                  child: Text(
                    'Respaldo',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 15,
                      fontWeight: FontWeight.normal,
                    ),
                  ),
                ),
              ],
            ),
            MouseRegion(
              onEnter: (_) => setState(() => _salirColor = const Color.fromARGB(255, 241, 218, 5)),
              onExit: (_) => setState(() => _salirColor = Colors.white),
              child: TextButton(
                onPressed: () {
                  Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => LoginPage()), // Redirige a la nueva página
                );
                },
                child: Text(
                  'Salir',
                  style: TextStyle(
                    color: _salirColor,
                    fontSize: 15,
                    fontWeight: FontWeight.normal,
                  ),
                ),
              ),
            ),
          ],
        ),
        backgroundColor: const Color.fromARGB(255, 15, 105, 60),
      ),
      body: SingleChildScrollView( // Para permitir desplazamiento si el contenido es grande
        child: Column(
          children: [
           

            SizedBox(height: 30), // Espacio antes de la nueva sección

            // Display success or error message
            if (_mensajeExito.isNotEmpty)
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: Text(
                  _mensajeExito,
                  style: TextStyle(color: Colors.green, fontSize: 16),
                  textAlign: TextAlign.center,
                ),
              ),
            Container(
              color: const Color.fromARGB(255, 15, 105, 60), 
              padding: EdgeInsets.symmetric(vertical: 20, horizontal: 30),
              child: Column(
                children: [
                  Text(
                    '"2024, Conmemoración de los 195 Años de la Fundación del Instituto Literario del Estado de México"',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 16,
                      fontStyle: FontStyle.italic,
                    ),
                    textAlign: TextAlign.center,
                  ),
                  SizedBox(height: 20),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                    children: [
                      // Vínculos de interés
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'VÍNCULOS DE INTERÉS',
                            style: TextStyle(
                              color: Colors.white,
                              fontWeight: FontWeight.bold,
                              fontSize: 16,
                            ),
                          ),
                          SizedBox(height: 10),
                          InkWell(
                            onTap: () {
                              // Acciones al pulsar
                            },
                            child: Text(
                              'SiTAA',
                              style: TextStyle(color: Colors.white, decoration: TextDecoration.underline),
                            ),
                          ),
                          SizedBox(height: 5),
                          InkWell(
                            onTap: () {
                              // Acciones al pulsar
                            },
                            child: Text(
                              'UAEMéx',
                              style: TextStyle(color: Colors.white, decoration: TextDecoration.underline),
                            ),
                          ),
                          SizedBox(height: 5),
                          InkWell(
                            onTap: () {
                              // Acciones al pulsar
                            },
                            child: Text(
                              'Delfos',
                              style: TextStyle(color: Colors.white, decoration: TextDecoration.underline),
                            ),
                          ),
                        ],
                      ),
                      // Coordinación de Tutoría Académica
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'COORDINACIÓN DE TUTORÍA ACADÉMICA',
                            style: TextStyle(
                              color: Colors.white,
                              fontWeight: FontWeight.bold,
                              fontSize: 16,
                            ),
                          ),
                          SizedBox(height: 10),
                          Row(
                            children: [
                              Icon(Icons.email, color: Colors.white, size: 20),
                              SizedBox(width: 5),
                              Text(
                                'tutoria_fi@uaemex.mx',
                                style: TextStyle(color: Colors.white),
                              ),
                            ],
                          ),
                          SizedBox(height: 5),
                          Row(
                            children: [
                              Icon(Icons.phone, color: Colors.white, size: 20),
                              SizedBox(width: 5),
                              Text(
                                '(722) 895 9749',
                                style: TextStyle(color: Colors.white),
                              ),
                            ],
                          ),
                        ],
                      ),
                      // Datos de contacto
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'DATOS DE CONTACTO',
                            style: TextStyle(
                              color: Colors.white,
                              fontWeight: FontWeight.bold,
                              fontSize: 16,
                            ),
                          ),
                          SizedBox(height: 10),
                          Row(
                            children: [
                              Icon(Icons.location_on, color: Colors.white, size: 20),
                              SizedBox(width: 5),
                              Text(
                                'Facultad de Ingeniería UAEM',
                                style: TextStyle(color: Colors.white),
                              ),
                            ],
                          ),
                          SizedBox(height: 5),
                          Text(
                            'Cerro de Coatepec S/N, Ciudad Universitaria C.P. 50100.\nToluca, Estado de México',
                            style: TextStyle(color: Colors.white),
                          ),
                          SizedBox(height: 5),
                          Row(
                            children: [
                              Icon(Icons.phone, color: Colors.white, size: 20),
                              SizedBox(width: 5),
                              Text(
                                '(722) 214 08 55 y 214 07 95',
                                style: TextStyle(color: Colors.white),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ],
                  ),
                ],
              ),
            ),
            SizedBox(height: 20), // Espacio al final
          ],
        ),
      ),
    );
  }
}
