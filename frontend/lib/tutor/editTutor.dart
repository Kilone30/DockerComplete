import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:atlas/session.dart';


class EditarTutorPage extends StatefulWidget {
  final Map<String, dynamic> docente;

  EditarTutorPage({required this.docente});

  @override
  _EditarTutorPageState createState() => _EditarTutorPageState();
}

class _EditarTutorPageState extends State<EditarTutorPage> {
  late TextEditingController _rfcController;
  late TextEditingController _correoController;
  late TextEditingController _curpController;

  bool _isLoading = false; // Para mostrar un indicador de carga

  @override
  void initState() {
    super.initState();
    _rfcController = TextEditingController(text: widget.docente['rfc']);
    _correoController = TextEditingController(text: widget.docente['correo_institucional']);
    _curpController = TextEditingController(text: widget.docente['curp']);
  }
Future<void> actualizarTutor() async {
  setState(() {
    _isLoading = true;
  });

  // URL con la ruta correcta para el docente
  final url = Uri.parse('https://mex-ico.mx/kernel/public/api/docentes/rfc/${widget.docente['rfc']}');

  final body = jsonEncode({
    'rfc': _rfcController.text,
    'correo_institucional': _correoController.text,
    'curp': _curpController.text,
  });

  try {
    final response = await http.put(
      url,
      headers: {'Content-Type': 'application/json'},
      body: body,
    );

    if (response.statusCode == 200) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Datos actualizados exitosamente'), backgroundColor: Colors.green),
      );

      // Esperar un momento antes de redirigir
      await Future.delayed(Duration(seconds: 1));

      // Regresar a la pantalla anterior (Lista de Tutores)
      Navigator.pop(context, true);
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error al actualizar datos'), backgroundColor: Colors.red),
      );
    }
  } catch (e) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text('Error: $e'), backgroundColor: Colors.red),
    );
  } finally {
    setState(() {
      _isLoading = false;
    });
  }
}


  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Editar Tutor')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            TextField(
              controller: _rfcController,
              decoration: InputDecoration(labelText: 'RFC'),
            ),
            TextField(
              controller: _correoController,
              decoration: InputDecoration(labelText: 'Correo Institucional'),
            ),
            TextField(
              controller: _curpController,
              decoration: InputDecoration(labelText: 'CURP'),
            ),
            SizedBox(height: 20),
            _isLoading
                ? CircularProgressIndicator()
                : ElevatedButton(
                    onPressed: actualizarTutor,
                    child: Text('Guardar Cambios'),
                  ),
          ],
        ),
      ),
    );
  }
}
