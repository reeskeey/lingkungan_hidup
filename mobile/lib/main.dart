import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'core/theme/app_theme.dart';
import 'providers/auth_provider.dart';
import 'providers/fasyankes_provider.dart';
import 'providers/map_provider.dart';
import 'providers/roadmap_provider.dart';
import 'providers/dashboard_provider.dart';
import 'views/auth/login_screen.dart';
import 'views/home/main_navigation_screen.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  runApp(const GisLimbahB3App());
}

class GisLimbahB3App extends StatelessWidget {
  const GisLimbahB3App({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()..tryAutoLogin()),
        ChangeNotifierProvider(create: (_) => FasyankesProvider()),
        ChangeNotifierProvider(create: (_) => MapProvider()),
        ChangeNotifierProvider(create: (_) => RoadmapProvider()),
        ChangeNotifierProvider(create: (_) => DashboardProvider()),
      ],
      child: Consumer<AuthProvider>(
        builder: (context, auth, _) {
          return MaterialApp(
            title: 'GIS Limbah B3 Medis - KLH/BPLH',
            debugShowCheckedModeBanner: false,
            theme: AppTheme.lightTheme,
            home: auth.isAuthenticated
                ? const MainNavigationScreen()
                : const LoginScreen(),
          );
        },
      ),
    );
  }
}
