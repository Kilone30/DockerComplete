import 'package:atlas/inicio.dart';
import 'package:atlas/main.dart';
import 'package:atlas/respaldos/descargas.dart';
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
      title: 'Actualizar',
      theme: ThemeData(
        useMaterial3: true,
        colorScheme: ColorScheme.fromSeed(
            seedColor: const Color.fromARGB(255, 34, 255, 244)),
      ),
      home: ActualizarPage(),
    );
  }
}

class ActualizarPage extends StatefulWidget {
  @override
  // ignore: library_private_types_in_public_api
  _ActualizarPageState createState() => _ActualizarPageState();
}

class _ActualizarPageState extends State<ActualizarPage> {
  Color _inicioColor = Colors.white;
  Color _salirColor = Colors.white;
  String? _selectedOption; 
  bool esTutor = false;
  String? _selectedTypeOption;
  String?_selectedTutorOption;
  String? _selectedAcademicGradeOption;
  String? _selectedGAOption;


  // Controladores para cada campo de texto
  final TextEditingController nombreController = TextEditingController();
  final TextEditingController apellidoPaternoController = TextEditingController();
  final TextEditingController apellidoMaternoController = TextEditingController();
  final TextEditingController correoController = TextEditingController();
  final TextEditingController telefonoController = TextEditingController();
  final TextEditingController carreraController = TextEditingController();

  bool showForm = false; // Controla cuándo mostrar el formulario editable

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
            SizedBox(height: 20),
            Align(
              alignment: Alignment.centerLeft, // O puedes usar Alignment.center
              child: Padding(
                padding: const EdgeInsets.only(left: 50.0), // Ajusta el padding según necesites
                child: Text(
                  'ACTUALIZAR TUTOR',
                  style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                ),
              ),
            ),
            // Código en el que agregamos un botón "Buscar" a la barra de búsqueda
Padding(
  padding: const EdgeInsets.symmetric(horizontal: 50.0), // Ajusta el padding según necesites
  child: Row(
    children: [
      Text(
        'Buscar tutor:',
        style: TextStyle(
          fontSize: 16,
          fontWeight: FontWeight.bold,
        ),
      ),
      SizedBox(width: 10), // Espacio entre el texto y el campo de búsqueda
      Expanded(
        child: TextField(
          controller: nombreController, // Opcional: puedes usar un controlador para acceder al texto ingresado
          decoration: InputDecoration(
            hintText: 'Escriba el nombre del tutor...',
            prefixIcon: Icon(Icons.search),
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(8.0),
            ),
            filled: true,
            fillColor: Colors.white,
          ),
        ),
      ),
      SizedBox(width: 10), // Espacio entre la barra y el botón
      ElevatedButton(
        onPressed: () {
          String nombreBusqueda = nombreController.text;
          print("Buscar: $nombreBusqueda");
          // Lógica de búsqueda futura, por ejemplo, conexión con la base de datos
          setState(() {
            showForm = true; // Muestra el formulario si se encuentra el tutor
          });
        },
        style: ElevatedButton.styleFrom(
                  backgroundColor: const Color.fromARGB(255, 186, 153, 21),
                ),
        child: Text(
                  'Buscar',
                  style: TextStyle(
                    color: Colors.white, // Cambia el color del texto del botón
                  ),
                ),
      ),
    ],
  ),
),

            

// Campos editables de los datos del tutor
SizedBox(height: 15),
Visibility(
  
  visible: showForm, // Controla cuándo mostrar el formulario
  child: Padding(
    padding: const EdgeInsets.symmetric(horizontal: 50.0),
    child: Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Align(
              alignment: Alignment.centerLeft, // O puedes usar Alignment.center
              child: Padding(
                padding: const EdgeInsets.only(left: 0.0), // Ajusta el padding según necesites
                child: Text(
                  'DATOS DEL TUTOR',
                  style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                ),
              ),
            ),
            SizedBox(height: 15),
        Text(
        'Nombre del tutor:',
        style: TextStyle(
          fontSize: 16,
          fontWeight: FontWeight.bold,
        ),
      ),
        TextField(
          controller: nombreController,
          decoration: InputDecoration(
            labelText: 'Nombre',
            border: OutlineInputBorder(),
          ),
        ),
        
        SizedBox(height: 15),
        Text(
        'Primer Apellido:',
        style: TextStyle(
          fontSize: 16,
          fontWeight: FontWeight.bold,
        ),
      ),
        TextField(
          controller: apellidoPaternoController,
          decoration: InputDecoration(
            labelText: 'Primer Apellido',
            border: OutlineInputBorder(),
          ),
        ),

        SizedBox(height: 15),
        Text(
        'Segundo Apellido:',
        style: TextStyle(
          fontSize: 16,
          fontWeight: FontWeight.bold,
        ),
      ),
        TextField(
          controller: apellidoMaternoController,
          decoration: InputDecoration(
            labelText: 'Segundo Apellido',
            border: OutlineInputBorder(),
          ),
        ),
        SizedBox(height: 15),
        Text(
        'Correo Institucional:',
        style: TextStyle(
          fontSize: 16,
          fontWeight: FontWeight.bold,
        ),
      ),
        TextField(
          controller: correoController,
          decoration: InputDecoration(
            labelText: 'Correo Institucional',
            border: OutlineInputBorder(),
          ),
        ),
        SizedBox(height: 15),
        Text(
        'Numero de Telelfono:',
        style: TextStyle(
          fontSize: 16,
          fontWeight: FontWeight.bold,
        ),
      ),
        TextField(
          controller: telefonoController,
          decoration: InputDecoration(
            labelText: 'Número de Teléfono',
            border: OutlineInputBorder(),
          ),
        ),
        SizedBox(height: 15),
        Text(
        'RFC:',
        style: TextStyle(
          fontSize: 16,
          fontWeight: FontWeight.bold,
        ),
      ),
      TextField(
          controller: telefonoController,
          decoration: InputDecoration(
            labelText: 'RFC',
            border: OutlineInputBorder(),
          ),
        ),
        SizedBox(height: 15),
        Text(
        'CURP:',
        style: TextStyle(
          fontSize: 16,
          fontWeight: FontWeight.bold,
        ),
      ),
      TextField(
          controller: telefonoController,
          decoration: InputDecoration(
            labelText: 'CURP',
            border: OutlineInputBorder(),
          ),
        ),

        Text(
  'Grado Academico:',
  style: TextStyle(
    fontSize: 16,
    fontWeight: FontWeight.bold,
  ),
),

Column(
  children: [
    RadioListTile<String>(
      title: const Text('Licenciatura'),
      value: 'Licenciatura',
      groupValue: _selectedGAOption,
      onChanged: (String? value) {
        setState(() {
          _selectedOption = value;
        });
      },
    ),
    RadioListTile<String>(
      title: const Text('Maestria'),
      value: 'Maestria',
      groupValue: _selectedGAOption,
      onChanged: (String? value) {
        setState(() {
          _selectedOption = value;
        });
      },
    ),
    RadioListTile<String>(
      title: const Text('Doctorado'),
      value: 'Doctorado',
      groupValue: _selectedGAOption,
      onChanged: (String? value) {
        setState(() {
          _selectedOption = value;
        });
      },
    ),
  ],
),


Text(
  'Genero:',
  style: TextStyle(
    fontSize: 16,
    fontWeight: FontWeight.bold,
  ),
),

Column(
  children: [
    RadioListTile<String>(
      title: const Text('Masculino'),
      value: 'Masculino',
      groupValue: _selectedOption,
      onChanged: (String? value) {
        setState(() {
          _selectedOption = value;
        });
      },
    ),
    RadioListTile<String>(
      title: const Text('Femenino'),
      value: 'Femenino',
      groupValue: _selectedOption,
      onChanged: (String? value) {
        setState(() {
          _selectedOption = value;
        });
      },
    ),
  ],
),

// Pregunta si es tutor
Text(
  'Tutor:',
  style: TextStyle(
    fontSize: 16,
    fontWeight: FontWeight.bold,
  ),
),

Column(
  children: [
    RadioListTile<String>(
      title: const Text('Sí'),
      value: 'Sí',
      groupValue: _selectedTutorOption,
      onChanged: (String? value) {
        setState(() {
          _selectedTutorOption = value;
        });
      },
    ),
    RadioListTile<String>(
      title: const Text('No'),
      value: 'No',
      groupValue: _selectedTutorOption,
      onChanged: (String? value) {
        setState(() {
          _selectedTutorOption = value;
        });
      },
    ),
  ],
),

// Si selecciona "Sí", muestra opciones de tipo de tutoría y grado académico
if (_selectedTutorOption == 'Sí') ...[
  // Selección del tipo de tutoría
  Text(
    'Selecciona tipo:',
    style: TextStyle(
      fontSize: 16,
      fontWeight: FontWeight.bold,
    ),
  ),
  Column(
    children: [
      RadioListTile<String>(
        title: const Text('Individual'),
        value: 'Individual',
        groupValue: _selectedTypeOption,
        onChanged: (String? value) {
          setState(() {
            _selectedTypeOption = value;
          });
        },
      ),
      RadioListTile<String>(
        title: const Text('Colaborativo'),
        value: 'Colaborativo',
        groupValue: _selectedTypeOption,
        onChanged: (String? value) {
          setState(() {
            _selectedTypeOption = value;
          });
        },
      ),
    ],
  ),

  SizedBox(height: 15),

  // Selección del grado académico
  Text(
    'Carrera:',
    style: TextStyle(
      fontSize: 16,
      fontWeight: FontWeight.bold,
    ),
  ),
  Column(
    children: [
      RadioListTile<String>(
        title: const Text('ICO'),
        value: 'ICO',
        groupValue: _selectedAcademicGradeOption,
        onChanged: (String? value) {
          setState(() {
            _selectedAcademicGradeOption = value;
          });
        },
      ),
      RadioListTile<String>(
        title: const Text('IME'),
        value: 'IME',
        groupValue: _selectedAcademicGradeOption,
        onChanged: (String? value) {
          setState(() {
            _selectedAcademicGradeOption = value;
          });
        },
      ),
      RadioListTile<String>(
        title: const Text('ISES'),
        value: 'ISES',
        groupValue: _selectedAcademicGradeOption,
        onChanged: (String? value) {
          setState(() {
            _selectedAcademicGradeOption = value;
          });
        },
      ),
      RadioListTile<String>(
        title: const Text('IA'),
        value: 'IA',
        groupValue: _selectedAcademicGradeOption,
        onChanged: (String? value) {
          setState(() {
            _selectedAcademicGradeOption = value;
          });
        },
      ),
      RadioListTile<String>(
        title: const Text('ICI'),
        value: 'ICI',
        groupValue: _selectedAcademicGradeOption,
        onChanged: (String? value) {
          setState(() {
            _selectedAcademicGradeOption = value;
          });
        },
      ),
      RadioListTile<String>(
        title: const Text('IE'),
        value: 'IE',
        groupValue: _selectedAcademicGradeOption,
        onChanged: (String? value) {
          setState(() {
            _selectedAcademicGradeOption = value;
          });
        },
      ),
    ],
  ),
],



        SizedBox(height: 20),
          ElevatedButton(
                onPressed: () {
                  // Aquí puedes agregar la lógica del botón en el futuro
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color.fromARGB(255, 186, 153, 21),
                ),
                child: Text(
                  'Actualizar',
                  style: TextStyle(
                    color: Colors.white, // Cambia el color del texto del botón
                  ),
                ),
              ),

      ],
    ),
  ),
),

            SizedBox(height: 30), // Espacio antes de la nueva sección
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
