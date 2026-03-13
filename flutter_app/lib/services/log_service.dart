import 'dart:io';
import 'package:path_provider/path_provider.dart';

class LogService {
  static int testCounter = 0;

  static Future<File> _getLogFile() async {
    final directory = await getApplicationDocumentsDirectory();
    final file = File('${directory.path}/app_debug_log.txt');

    if (!await file.exists()) {
      await file.create();
    }

    return file;
  }

  static Future<void> log(String message) async {
    final file = await _getLogFile();
    final timestamp = DateTime.now().toIso8601String();
    final logMessage = "[$timestamp] $message\n";

    await file.writeAsString(logMessage, mode: FileMode.append);
  }

  static Future<void> startNewTest() async {
    testCounter++;
    await log("===== TEST $testCounter START =====");
  }
}
