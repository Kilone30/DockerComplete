import 'dart:io';
// ignore: depend_on_referenced_packages
import 'package:http/http.dart' as http;
import 'package:atlas/inicio.dart';
import 'package:atlas/main.dart';
import 'package:atlas/respaldos/descargas.dart';
import 'package:atlas/tutor/actualizar.dart';
import 'package:atlas/tutor/alta.dart';
import 'package:atlas/tutor/consultar.dart';
import 'package:atlas/tutor/colaborativos.dart';
import 'package:atlas/tutorados/nuevo_ingreso.dart';
import 'package:excel/excel.dart';
import 'package:file_selector/file_selector.dart';
import 'package:flutter/material.dart';
import 'dart:typed_data';
import 'package:path_provider/path_provider.dart';
import 'dart:convert';
import 'package:flutter/foundation.dart' show kIsWeb;
import 'package:file_saver/file_saver.dart';
import 'package:atlas/session.dart';



class MyApp extends StatelessWidget {
  const MyApp({super.key});


  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Población Estudiantil',
      theme: ThemeData(
        useMaterial3: true,
        colorScheme: ColorScheme.fromSeed(
            seedColor: const Color.fromARGB(255, 34, 255, 244)),
      ),
      home: PoblacionPage(),
    );
  }
}

class PoblacionPage extends StatefulWidget {
  @override
  // ignore: library_private_types_in_public_api
  _PoblacionPageState createState() => _PoblacionPageState();
}

class _PoblacionPageState extends State<PoblacionPage> {
    Color _inicioColor = Colors.white;
  Color _salirColor = Colors.white;
  String _mensajeExito = '';
  File? selectedFile;
  List<List<String>> excelData = [];
  List<Map<String, dynamic>> estudiantes = [];
  List<Map<String, dynamic>> estudiantesFiltrados = [];
  bool _isLoading = false;

  TextEditingController _searchController = TextEditingController();
  @override
  void initState() {
    super.initState();
    obtenerNuevosIngresos();
  }

 // 🔍 Obtener alumnos desde la API
 Future<List<Map<String, dynamic>>> obtenerNuevosIngresos() async {
  final String url = 'http://127.0.0.1:8001/api/v1/alumnos';

  try {
    final response = await http.get(Uri.parse(url));
    if (response.statusCode == 200) {
      final Map<String, dynamic> responseData = jsonDecode(response.body);

      if (responseData.containsKey('data')) {
        final List<dynamic> estudiantesData = responseData['data'];
        return estudiantesData.cast<Map<String, dynamic>>();
      } else {
        print('La respuesta no contiene la clave "data"');
        return [];
      }
    } else {
      print('Error al obtener estudiantes: ${response.statusCode} - ${response.body}');
      return [];
    }
  } catch (e) {
    print('Error en la solicitud: $e');
    return [];
  }
}

// Para subir la nueva población desde un archivo Excel y procesar cambios
Future<void> subirDatos(List<List<String>> data, List<Map<String, dynamic>> alumnosActualesApi) async {
  final String url = 'http://127.0.0.1:8001/api/v1/alumnos';
  List<List<String>> errores = [];
  List<Map<String, dynamic>> eliminados = [];

  final Set<String> cuentasApi = alumnosActualesApi.map((e) => e['num_cuenta'].toString()).toSet();
  final Set<String> cuentasExcel = {};

  for (var alumno in data) {
    if (alumno[0].trim().isEmpty) {
      print('Alumno ignorado: Falta número de cuenta');
      continue;
    }

    String numCuenta = alumno[0];
    cuentasExcel.add(numCuenta);

    if (cuentasApi.contains(numCuenta)) {
      print('Alumno $numCuenta ya existe. Ignorado.');
      continue;
    }

    Map<String, String> alumnoData = {
      "num_cuenta": alumno[0],
      "nombre": alumno[1],
      "primer_apellido": alumno[2],
      "segundo_apellido": alumno[3],
      "genero": alumno[4],
      "correo_personal": alumno[5],
      "correo_institucional": alumno[6],
      "licenciatura": alumno[7],
      "plan_estudio": alumno[8]
    };

    try {
      final response = await http.post(
        Uri.parse(url),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode(alumnoData),
      );

      if (response.statusCode == 201) {
        print('Alumno $numCuenta subido exitosamente.');
      } else {
        print('Error con alumno $numCuenta');
        errores.add(alumno);
      }
    } catch (e) {
      print('Error en la solicitud para $numCuenta: $e');
      errores.add(alumno);
    }
  }

  // Detectar eliminados
  for (var alumno in alumnosActualesApi) {
    String numCuenta = alumno['num_cuenta'].toString();
    if (!cuentasExcel.contains(numCuenta)) {
      eliminados.add(alumno);
    }
  }

  if (errores.isNotEmpty) {
    await generarExcelErrores(errores);
  }

  if (eliminados.isNotEmpty) {
    await generarPgdump(eliminados);
  }
}

Future<void> generarPgdump(List<Map<String, dynamic>> eliminados) async {
  try {
    final buffer = StringBuffer();

    for (var alumno in eliminados) {
        String formatValue(dynamic value) {
    if (value == null || value.toString().toLowerCase() == 'null') {
      return 'NULL';
    } else {
      return "'$value'";
    }
  }
buffer.writeln("DELETE FROM alumnos WHERE num_cuenta = '${alumno['num_cuenta']}';");
    }

    if (kIsWeb) {
      // Para Flutter Web, usar FileSaver para descargar el archivo
      final bytes = Uint8List.fromList(buffer.toString().codeUnits);
      final result = await FileSaver.instance.saveFile(
        name: 'eliminados_pgdump.sql',
        bytes: bytes,
      );
      print('Archivo descargado en web: $result');
    } else {
      // Para plataformas móviles o escritorio, usar path_provider
      final directory = await getApplicationDocumentsDirectory();
      final path = '${directory.path}/eliminar_alumnos.sql';
      final file = File(path);
      await file.writeAsString(buffer.toString());
      print('Archivo pgdump generado en: $path');
    }
  } catch (e) {
    print('Error al generar el archivo pgdump: $e');
  }
}



Future<void> generarExcelErrores(List<List<String>> errores) async {
  try {
    final excel = Excel.createExcel();
    final sheet = excel[excel.getDefaultSheet()!];

    // Agregar datos
    for (final alumno in errores) {
      sheet.appendRow(alumno.map((e) => TextCellValue(e)).toList());
    }
    // Convertir a bytes
    final bytes = excel.encode()!;

    if (kIsWeb) {
      // Usamos file_saver para guardar el archivo
      final result = await FileSaver.instance.saveFile(
        name: 'alumnos_errores.xlsx',
        bytes: Uint8List.fromList(bytes)
      );
      print(result); // Se usa el resultado para verificar si la descarga fue exitosa
      return;
    }

  } catch (e) {
    print('⚠️ Error al generar Excel: $e');
  }
}


  //  Función para seleccionar un archivo Excel
  Future<void> _pickFile() async {
    try {
      final XFile? file = await openFile(
        acceptedTypeGroups: [
          XTypeGroup(label: 'Excel', extensions: ['xlsx', 'xls']),
        ],
      );

      if (file != null) {
        final bytes = await file.readAsBytes();
        _loadExcelDataFromBytes(bytes);
      }
    } catch (e) {
      print('Error al seleccionar archivo: $e');
    }
  }

  //  Función para cargar datos desde un archivo Excel
  void _loadExcelDataFromBytes(Uint8List bytes) {
    try {
      final excel = Excel.decodeBytes(bytes);
      List<List<String>> tempData = [];
      for (var table in excel.tables.keys) {
        var sheet = excel.tables[table]!;
        for (var row in sheet.rows) {
          List<String> rowData = [];
          for (var cell in row) {
            rowData.add(cell?.value?.toString() ?? '');
          }
          tempData.add(rowData);
        }
        break;
      }
      setState(() {
        excelData = tempData;
      });
    } catch (e) {
      print('Error al cargar datos de Excel desde bytes: $e');
    }
  }

  //  Función para subir el archivo Excel
  void _subirArchivo() async {
    try {
      final alumnosActualesApi = await obtenerNuevosIngresos();
      await subirDatos(excelData, alumnosActualesApi);
      setState(() {
        _mensajeExito = 'Archivo subido exitosamente.';
      });
    } catch (e) {
      print('Error al subir el archivo: $e');
      setState(() {
        _mensajeExito = 'Error al subir el archivo.';
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
      
// Cuerpo del Scaffold
body: SingleChildScrollView(
  child: Column(
    children: [
      SizedBox(height: 20), // Espacio antes del título

      // Título "Actualizar Población"
      Text(
        'Empleados', // Texto del título
        style: TextStyle(
          fontSize: 24, // Tamaño de letra más grande
          fontWeight: FontWeight.bold, // Negrita
          color: Colors.black, // Color del texto
        ),
      ),

      SizedBox(height: 20), // Espacio después del título

      // 🔎 Barra de búsqueda
            Padding(
              padding: const EdgeInsets.all(8.0),
              child: TextField(
                controller: _searchController,
                decoration: InputDecoration(
                  labelText: 'Buscar empleado',
                  hintText: 'Nombre o número del empleado',
                  prefixIcon: Icon(Icons.search),
                  border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                ),
              ),
            ),

            // 📋 Lista de alumnos (Scrollable dentro de un Container)
            SizedBox(
              height: 300, // 🔥 Evita overflow
              child: _isLoading
                  ? Center(child: CircularProgressIndicator())
                  : estudiantesFiltrados.isEmpty
                      ? Center(child: Text('No hay empleados disponibles'))
                      : ListView.builder(
                          itemCount: estudiantesFiltrados.length,
                          itemBuilder: (context, index) {
                            final alumno = estudiantesFiltrados[index];
                            return Card(
                              margin: EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                              child: ListTile(
                                title: Text('${alumno["nombre"]} ${alumno["primer_apellido"]}'),
                                subtitle: Text('Cuenta: ${alumno["num_cuenta"]}'),
                                leading: Icon(Icons.person),
                              ),
                            );
                          },
                        ),
            ),
            SizedBox(height: 20),

      // Botón para seleccionar archivo
      ElevatedButton(
        onPressed: _pickFile,
        style: ElevatedButton.styleFrom(
          backgroundColor: const Color.fromARGB(192, 222, 15, 8),
          foregroundColor: Colors.white,
        ),
        child: Text('Seleccionar Archivo'),
      ),
            if (excelData.isNotEmpty) ...[
  SizedBox(height: 20),
  Container(
    padding: EdgeInsets.all(10),
    margin: EdgeInsets.symmetric(horizontal: 20),
    decoration: BoxDecoration(
      color: Colors.grey[200],
      borderRadius: BorderRadius.circular(10),
      boxShadow: [
        BoxShadow(
          // ignore: deprecated_member_use
          color: Colors.grey.withOpacity(0.5),
          spreadRadius: 2,
          blurRadius: 5,
          offset: Offset(0, 3),
        ),
      ],
    ),
    child: Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          kIsWeb
              ? 'Archivo cargado desde Web'
              : 'Archivo seleccionado: ${selectedFile?.path.split('/').last ?? "No disponible"}',
          style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
        ),
        // Título "Actualizar Población
        SizedBox(height: 10),
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          child: DataTable(
            columns: excelData[0]
                .map((header) => DataColumn(label: Text(header)))
                .toList(),
            rows: excelData.skip(1).map((row) {
              return DataRow(
                cells: row.map((cell) => DataCell(Text(cell))).toList(),
              );
            }).toList(),
          ),
        ),
      ],
    ),
  ),
SizedBox(height: 20),




        // Botón para subir archivo
        ElevatedButton(
          onPressed: _subirArchivo,
          style: ElevatedButton.styleFrom(
            backgroundColor: const Color.fromARGB(255, 15, 105, 60),
            foregroundColor: Colors.white,
          ),
          child: Text('Subir Archivo'),
        ),
        
        SizedBox(height: 10),
        
        // Mensaje de éxito al subir archivo
if (_mensajeExito.isNotEmpty) 
  Text(
    _mensajeExito,
    style: TextStyle(
      color: Colors.green,
      fontWeight: FontWeight.bold,
      fontSize: 16,
    ),
  ),

      ],



  
            SizedBox(height: 30), // Espacio antes de la nueva sección
            Container(
              color: const Color.fromARGB(192, 222, 15, 8), 
              padding: EdgeInsets.symmetric(vertical: 20, horizontal: 30),
              child: Column(
                children: [
                  Text(
                    'Contacto del Área de Recursos Humanos',
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
                      
                      // Coordinación de Tutoría Académica
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Contacto del Área de Recursos Humanos',
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
                                'rh@empresa.com',
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
                                '(000) 123 4567',
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
            SizedBox(height: 20), // Espacio al final // Espacio al final
          ],
        ),
      ),
    );
  }
}
