import 'package:atlas/inicio.dart';
import 'package:atlas/main.dart';
import 'package:atlas/respaldos/descargas.dart';
import 'package:atlas/tutor/actualizar.dart';
import 'package:atlas/tutor/consultar.dart';
import 'package:atlas/tutor/colaborativos.dart';
import 'package:atlas/tutor/individuales';
import 'package:atlas/tutorados/nuevo_ingreso.dart';
import 'package:atlas/tutorados/poblacion.dart';
import 'package:flutter/material.dart';
// ignore: depend_on_referenced_packages
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:atlas/session.dart';


class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Alta',
      theme: ThemeData(
        useMaterial3: true,
        colorScheme: ColorScheme.fromSeed(
            seedColor: const Color.fromARGB(255, 34, 255, 244)),
      ),
      home: AltaPage(),
    );
  }
}

class AltaPage extends StatefulWidget {
  @override
  // ignore: library_private_types_in_public_api
  _AltaPageState createState() => _AltaPageState();
}

class _AltaPageState extends State<AltaPage> {
  Color _inicioColor = Colors.white;
  Color _salirColor = Colors.white;

  final TextEditingController _searchController = TextEditingController();
  List<dynamic> _docentes = [];
  List<dynamic> _docentesFiltrados = [];
  bool _isLoading = false;

  // Lista de equipos colaborativos para selección
  List<String> _equiposColaborativos = ['ICO', 'IEL', 'IME', 'ICI', 'ISES','IIA']; // Equipos colaborativos disponibles

  // 🔍 Función para obtener todos los docentes
  Future<void> guardarCache(List<dynamic> docentes) async {
  final prefs = await SharedPreferences.getInstance();
  prefs.setString('docentes', jsonEncode(docentes));
}

Future<void> cargarCache() async {
  final prefs = await SharedPreferences.getInstance();
  final data = prefs.getString('docentes');
  if (data != null) {
    setState(() {
      _docentes = jsonDecode(data);
      _docentesFiltrados = List.from(_docentes);
    });
  }
}


  Future<List<dynamic>> procesarDatos(String responseBody) async {
    final decoded = jsonDecode(responseBody);
    if (decoded is Map<String, dynamic> && decoded.containsKey('data')) {
      return List<dynamic>.from(decoded['data']);
    }
    return [];
  }
  Future<void> editarRFC(String rfcOriginal, String nuevoRFC) async {
  final url = Uri.parse('http://127.0.0.1:8001/api/v1/docentes/rfc/{rfc}');
  final headers = {
    'accept': 'application/json',
    'Authorization': 'Bearer ${Session.token}',
  };

  final body = jsonEncode({
    'rfc_control_escolar': nuevoRFC,
    // Agrega otros campos si es necesario
  });

  try {
    final response = await http.put(url, headers: headers, body: body);


    if (response.statusCode == 200) {
      // ignore: use_build_context_synchronously
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('RFC actualizado exitosamente')),
        
      );
      obtenerDocentes(); // Refresca la lista de docentes
    } else {
      // ignore: use_build_context_synchronously
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error al actualizar RFC: ${response.statusCode}')),
      );
    }
  } catch (e) {
    // ignore: use_build_context_synchronously
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text('Error inesperado: $e')),
    );
  }
}

Future<void> obtenerDocentes() async {
  print(Session.token);
  setState(() {
    _isLoading = true;
  });

  try {
    final response = await http.get(
      Uri.parse('http://127.0.0.1:8001/api/v1/docentes'),
      headers: {
        'Authorization': 'Bearer ${Session.token}',
        'Accept': 'application/json',
      },
    );
    if (response.statusCode == 200) {
      final responseData = await procesarDatos(response.body);
      setState(() {
        _docentes = responseData;
        _docentesFiltrados = List.from(_docentes);
      });
    } else {
      print('Error ${response.statusCode} al obtener docentes');
    }
  } catch (e) {
    print('Error: $e');
  } finally {
    setState(() {
      _isLoading = false;
    });
  }
}


  // 🔍 Función para buscar docentes por RFC o nombre
  void buscarDocentes(String query) {
    if (query.isEmpty) {
      setState(() {
        _docentesFiltrados = _docentes; // Si la búsqueda está vacía, mostramos todos
      });
    } else {
      final filteredList = _docentes.where((docente) {
        final nombreCompleto = "${docente['nombre']} ${docente['primer_apellido']} ${docente['segundo_apellido'] ?? ''}";
        // Filtrar por RFC o por nombre
        return docente['rfc'].toLowerCase().contains(query.toLowerCase()) || 
               nombreCompleto.toLowerCase().contains(query.toLowerCase());
      }).toList();

      setState(() {
        _docentesFiltrados = filteredList; // Actualizamos la lista de docentes filtrados
      });
    }
  }
  
  // ➕ Función para agregar tutor al equipo
  Future<void> agregarTutorAEquipo(String rfc, String licenciatura) async {
    final url = Uri.parse('http://127.0.0.1:8001/api/v1/tutores/rfc/$rfc/tutor-equipo'); 

    final headers = {
      'accept': 'application/json',
      'Authorization': 'Bearer ${Session.token}',
    };

    final body = jsonEncode({'licenciatura': licenciatura
                              });
    print(licenciatura);
    print(rfc);
    
    try {
      final response = await http.put(url, headers: headers, body: body);

      if (response.statusCode == 200) {
        // ignore: use_build_context_synchronously
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Tutor agregado exitosamente')),
        );
        obtenerDocentes(); // Refrescamos la lista de docentes
      } else {
        // ignore: use_build_context_synchronously
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error al agregar tutor: ${response.statusCode} - ${response.body}')),
        );
      }
    } catch (e) {
      // ignore: use_build_context_synchronously
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error inesperado: $e')),
      );
    }
  }

  // ➖ Función para eliminar tutor del equipo
Future<void> eliminarTutorDeEquipo(String rfc) async {
  final urlGet = Uri.parse('http://127.0.0.1:8001/api/v1/tutores/rfc/$rfc/tutorados'); // API GET para obtener los tutorados por RFC del tutor
  final urlPut = Uri.parse('http://127.0.0.1:8001/api/v1/tutores/rfc/$rfc/cambio-tutor'); // API PUT para actualizar tutorados
  final urlDelete = Uri.parse('http://127.0.0.1:8001/api/v1/tutores/rfc/$rfc'); // API DELETE para eliminar el tutor

  final headers = {
    'accept': 'application/json',
    'Authorization': 'Bearer ${Session.token}',
  };

  try {
    // Paso 1: Obtener los tutorados del tutor actual
    final responseGet = await http.get(urlGet, headers: headers);
    if (responseGet.statusCode == 200) {
      // Parsear la respuesta JSON
      final Map<String, dynamic> data = jsonDecode(responseGet.body);
      // Suponiendo que la lista de tutorados está bajo la clave 'tutorados'
      List<dynamic> tutorados = data['data'] ?? [];

      // Si tiene tutorados,  reasignarlos al tutor "sin tutor"
      if (tutorados.isNotEmpty) {
        for (var tutorado in tutorados) { 
          final tutoradoData = {
            'num_cuenta': tutorado['num_cuenta'],
            'rfc_destino': 'SINTUTOR00000', // Asignar al nuevo tutor "sin tutor"
          };
          // Realizar el PUT para cada tutorado
          final responsePut = await http.put(urlPut,
              headers: headers,
              body: jsonEncode(tutoradoData));

          // Verificar si el PUT fue exitoso
          if (responsePut.statusCode == 200) {
          } else {
            // Manejar el error del PUT
            // ignore: use_build_context_synchronously
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text('Error al actualizar tutorado: ${responsePut.statusCode}')),
            );
            return;
          }

          // Esperar un pequeño tiempo entre solicitudes PUT para evitar posibles problemas de concurrencia
          await Future.delayed(Duration(milliseconds: 500)); // Retraso de 500 ms
        }
      }

      // Paso 2: Eliminar al tutor
      final responseDelete = await http.delete(urlDelete, headers: headers);

      if (responseDelete.statusCode == 200 || responseDelete.statusCode == 204) {
        // El tutor fue eliminado exitosamente
        // ignore: use_build_context_synchronously
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Tutor eliminado exitosamente')),
        );
        obtenerDocentes(); // Refrescamos la lista de docentes
      } else {
        // Si ocurre un error al eliminar al tutor
        // ignore: use_build_context_synchronously
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error al eliminar tutor: ${responseDelete.statusCode}')),
        );
      }
    } else {
      // ignore: use_build_context_synchronously
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error al obtener tutorados: ${responseGet.statusCode}')),
      );
    }
  } catch (e) {
    // Manejar cualquier error inesperado
    // ignore: use_build_context_synchronously
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text('Error inesperado: $e')),
    );
  }
}


  // 🖥️ *Interfaz*
  @override
  void initState() {
    super.initState();
    obtenerDocentes(); // Llamamos a la API para obtener todos los docentes cuando se carga la página
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
                  '    N    E   S   T   L   E    | ',
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
                      '',
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
                      if (result == 'eliminar') {
                        Navigator.push(
                          context,
                          MaterialPageRoute(builder: (context) => ColaborativoPage()),
                        );
                      } else {
                        // Aquí puedes agregar la navegación para otras opciones
                        print(result);
                      }
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
                      if (result == 'individual') {
                        Navigator.push(
                          context,
                          MaterialPageRoute(builder: (context) => IndividualPage()),
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
                        value: 'consultas',
                        child: Text('Consultar')
                       ),
                    ],
                    child: Text(
                      'Supervisor',
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
                      child: Text('Empleados'),
                    ),
                    PopupMenuItem<String>(
                      value: 'nuevo_ingreso',
                      child: Text('Nuevos Empleados'),
                    ),
                  ],
                  child: Text(
                    'Empleado',
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
                    '',
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
                  Navigator.pushReplacement(
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
        backgroundColor: const Color.fromARGB(192, 222, 15, 8),
      ),
      body: Padding(
        padding: EdgeInsets.all(10),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Campo de búsqueda
            TextField(
              controller: _searchController,
              decoration: InputDecoration(
                labelText: 'Buscar por RFC o nombre',
                prefixIcon: Icon(Icons.search),
                border: OutlineInputBorder(),
              ),
              onChanged: buscarDocentes,
            ),
            SizedBox(height: 10),
            // Lista de docentes
            Expanded(
              child: _isLoading
                  ? Center(child: CircularProgressIndicator())
                  : ListView.builder(
                      itemCount: _docentesFiltrados.length,
                      itemBuilder: (context, index) {
                        final docente = _docentes[index];
                        final nombreCompleto =
                            "${docente['nombre']} ${docente['primer_apellido']} ${docente['segundo_apellido'] ?? ''}";

                        // Controlador para RFC editable
                        TextEditingController rfcCeController =
                            TextEditingController(text: docente['rfc']);

                        return Card(
                          margin: EdgeInsets.symmetric(vertical: 10),
                          elevation: 4,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: Padding(
                            padding: const EdgeInsets.all(16.0),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  'Docente: $nombreCompleto',
                                  style: TextStyle(
                                      fontSize: 18, fontWeight: FontWeight.bold),
                                    ),
                                SizedBox(height: 10),
                                Text(
                                     'RFC: ${docente['rfc']}',
                                      style: TextStyle(fontSize: 16),
                                    ),

                                TextField(
                                  controller: rfcCeController,
                                  decoration: InputDecoration(
                                    labelText: 'RFC',
                                    suffixIcon: Icon(Icons.edit),
                                  ),
                                ),
                                SizedBox(height: 10),
                                Text('Correo: ${docente['correo_institucional'] ?? 'No disponible'}'),
                                Text('CURP: ${docente['curp'] ?? 'No disponible'}'),

                                SizedBox(height: 20),

                                // Botón para confirmar la edición del RFC
                                ElevatedButton(
                                  onPressed: () {
                                    String nuevoRFC = rfcCeController.text;
                                    if (nuevoRFC.isNotEmpty) {
                                      editarRFC(docente['rfc'], nuevoRFC); // Llamada a la función para editar RFC
                                    } else {
                                      ScaffoldMessenger.of(context).showSnackBar(
                                        SnackBar(content: Text('Por favor, ingrese un RFC válido')),
                                      );
                                    }
                                  },
                                  child: Text('Confirmar edición'),
                                ),

                                SizedBox(height: 20),
                                // Botones de equipo para cada docente
                                Wrap(
                                  spacing: 10.0,
                                  children: _equiposColaborativos.map((equipo) {
                                    return ElevatedButton(
                                      onPressed: () {
                                        setState(() {
                                          if (docente['equipoSeleccionado'] == equipo) {
                                            docente['equipoSeleccionado'] = null;
                                          } else {
                                            docente['equipoSeleccionado'] = equipo;
                                          }
                                        });
                                      },
                                      style: ElevatedButton.styleFrom(
                                        backgroundColor: docente['equipoSeleccionado'] == equipo
                                            ? const Color.fromARGB(255, 58, 179, 24)
                                            : const Color.fromARGB(255, 255, 255, 255),
                                      ),
                                      child: Text(equipo),
                                    );
                                  }).toList(),
                                ),
                                SizedBox(height: 20),

                          docente['tutor'] == true
                              ? Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      'Equipo Colaborativo: ${docente['equipo_colaborativo']?['licenciatura'] ?? 'No asignado'}'
,
                                      style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                                    ),
                                    ElevatedButton(
                                      onPressed: () {
                                        eliminarTutorDeEquipo(docente['rfc']);
                                      },
                                      style: ElevatedButton.styleFrom(
                                        backgroundColor: Colors.red,
                                      ),
                                      child: Text(
                                        'Eliminar como Tutor',
                                        style: TextStyle(color: Colors.white),
                                      ),
                                    ),
                                  ],
                                )
                              : ElevatedButton(
                                  onPressed: () {
                                    if (docente['equipoSeleccionado'] != null) {
                                      print("Equipo seleccionado: ${docente['equipoSeleccionado']}");
                                      agregarTutorAEquipo(docente['rfc'], docente['equipoSeleccionado']);
                                    }
                                  },
                                  child: Text(
                                    'Agregar como Tutor',
                                    style: TextStyle(color: const Color.fromARGB(255, 0, 0, 0)),
                                  ),
                                ),
                              ],
                            ),
                          ),
                        );
                      },
                    ),
            ),
          ],
        ),
      ),
    );
  }
}
